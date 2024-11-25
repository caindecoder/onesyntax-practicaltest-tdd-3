<?php

namespace Tests\Feature;

use App\Jobs\SendPostEmails;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Subscriptions\Interactors\CreateSubscriptionInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateSubscriptionTest extends TestCase
{
    use RefreshDatabase;
    private Subscription $subscription;
    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $websiteData = Website::create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);
        $this->subscription = Subscription::query()->create([
            'email' => 'user01@example.com',
            'website_id' => $websiteData->id,
        ]);
    }

    // USER CREATES SUBSCRIPTION TESTS

    #[Test]
    public function can_not_create_subscription_with_invalid_data()
    {
        try {
            $createSubscriptionRequest = CreateSubscriptionRequest::validateAndCreate([
                'email' => '',
                'website_id' => ''
            ]);
            $createSubscriptionInteractor = new CreateSubscriptionInteractor;
            $createSubscriptionInteractor->execute($createSubscriptionRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
            $this->assertArrayHasKey('website_id', $exception->errors());
        }
    }

    #[Test]
    public function can_not_create_subscription_with_duplicate_data()
    {
        Website::factory()->create();
        Subscription::query()->create([
            'email' => 'user@example.com',
            'website_id' => $this->subscription->getKey(),
        ]);

        try {
            $createSubscriptionRequest = CreateSubscriptionRequest::validateAndCreate([
                'email' => 'user@example.com',
                'wesite_id' => $this->subscription->getKey(),
            ]);

            $createSubscriptionInteractor = new CreateSubscriptionInteractor;
            $createSubscriptionInteractor->execute($createSubscriptionRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
        }
    }

    #[Test]
    public function can_create_subscription()
    {
        $website = Website::factory()->create();
        $createSubscriptionRequest = CreateSubscriptionRequest::validateAndCreate([
            'email' => 'user@example.com',
            'website_id' => $website->id,
        ]);
        $createSubscriptionInteractor = new CreateSubscriptionInteractor();
        $createSubscriptionInteractor->execute($createSubscriptionRequest);

        $this->assertDatabaseHas('subscriptions', [
            'email' => $createSubscriptionRequest->email,
            'website_id' => $createSubscriptionRequest->websiteId,
        ]);
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
