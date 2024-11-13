<?php

namespace Tests\Feature;

use App\Jobs\SendPostEmails;
use App\Mail\PostPublished;
use App\Mail\SubscriptionAdded;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Emails\Interactors\SendEmailInteractor;
use Domain\Subscriptions\Interactors\CreateSubscriptionInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Domain\ValidationExceptions\ValidationException;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();

        $websiteData = Website::create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);

        $this->subscriptionData = [
            'email' => 'user@example.com',
            'website_id' => $websiteData->id,
        ];
    }

    // USER CREATES SUBSCRIPTION TESTS

    #[Test]
    public function can_not_create_subscription_with_invalid_data()
    {
        $request = new CreateSubscriptionRequest;

        $request->email = '';
        $request->website_id = '';

        try {
            $interactor = new CreateSubscriptionInteractor;
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The email field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_subscription_with_duplicate_data()
    {
        Subscription::create($this->subscriptionData);

        try {
            $request = new CreateSubscriptionRequest;
            $request->email = $this->subscriptionData['email'];
            $request->website_id = $this->subscriptionData['website_id'];
            $interactor = new CreateSubscriptionInteractor;
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Subscription already exists for this email and website.', $e->getMessage());
        }
    }

    #[Test]
    public function can_create_subscription_with_valid_email_and_website_id()
    {
        $request = new CreateSubscriptionRequest;
        $request->email = $this->subscriptionData['email'];
        $request->website_id = $this->subscriptionData['website_id'];

        $interactor = new CreateSubscriptionInteractor;
        $subscription = $interactor->execute($request);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($request->email, $subscription->email);
        $this->assertEquals($request->website_id, $subscription->website_id);
    }

    #[Test]
    public function can_send_confirmation_email_when_a_subscription_is_added(): void
    {
        Mail::fake();

        $request = new CreateSubscriptionRequest;
        $request->email = $this->subscriptionData['email'];
        $request->website_id = $this->subscriptionData['website_id'];

        $interactor = new CreateSubscriptionInteractor;
        $interactor->execute($request);

        $subscriptions = Subscription::all();
        foreach ($subscriptions as $subscription) {
            Mail::assertSent(SubscriptionAdded::class,1);
        }
    }

    #[Test]
    public function cannot_send_duplicate_confirmation_emails_to_subscribers(): void
    {
        Mail::fake();
        $request = new CreateSubscriptionRequest;
        $request->email = $this->subscriptionData['email'];
        $request->website_id = $this->subscriptionData['website_id'];
        $interactor = new CreateSubscriptionInteractor;
        $interactor->execute($request);

        $subscriptions = Subscription::all();
        try {
            foreach ($subscriptions as $subscription) {
                Mail::assertSent(SubscriptionAdded::class, $subscription->email);
            }
        } catch (ValidationException $e) {
            $this->fail('Duplicate emails were sent to subscribers.', $e->getMessage());
        }
    }

    #[Test]
    public function can_only_send_one_email_to_each_subscriber(): void
    {
        Mail::fake();
        $post = Post::first();
        $subscribers = Subscription::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostPublished($post));

        }
        foreach ($subscribers as $subscriber) {
            Mail::assertSent(PostPublished::class, function ($mail) use ($subscriber) {
                return $mail->hasTo($subscriber->email);
            });
        }
        $this->assertCount(count($subscribers), Mail::sent(PostPublished::class));
    }

    #[Test]
    public function can_use_queues_to_schedule_sending_in_the_background(): void
    {
        Queue::fake();
        $website = Website::factory()->create();
        $post = Post::factory()->create([
            'website_id' => $website->id
        ]);
        $subscribers = Subscription::factory()->count(2)->create([
            'website_id' => $website->id
        ]);

        foreach ($subscribers as $subscriber) {
            dispatch(new SendPostEmails($subscriber, $post));
        }

        Queue::assertPushed(SendPostEmails::class, 2);
    }




}
