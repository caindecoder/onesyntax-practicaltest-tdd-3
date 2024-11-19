<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Posts\Interactors\PublishPostInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\PendingMailFake;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PublishPostTest extends TestCase
{
    use RefreshDatabase;

    private array $postData;
    private int $websiteId;

    protected function setUp(): void
    {
        parent::setUp();
        $website = Website::create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);
        $this->websiteId = $website->id;
        $this->postData = [
            'title' => 'Test Post Title',
            'description' => 'Test Post Description',
            'website_id' => $this->websiteId,
        ];

        Subscription::factory(2)
            ->create([
                'website_id' => $website->getKey()
            ]);
    }

    // USER PUBLISHES POST TESTS

    #[Test]
    public function can_send_email_to_relevant_subscribers_when_a_post_is_published(): void
    {
        Mail::fake();
        $website01 = Website::factory()
            ->create();
        $relevantSubscription = Subscription::factory()
            ->create([
                'website_id' => $website01->getKey()
            ]);
        $post = Post::factory()
            ->create([
            'website_id' => $website01->getKey(),
        ]);


        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertSent(PostPublished::class, 1);
        foreach ($relevantSubscription as $subscription) {
            $this->assertDatabaseHas('sent_emails', [
                'subscription_id' => $relevantSubscription->id,
                'post_id' => $post->id,
            ]);
        }
        Mail::assertSent(PostPublished::class, function ($mail) use
        ($relevantSubscription) {
            return $mail->hasTo($relevantSubscription);
        });
    }

    #[Test]
    public function can_not_send_email_to_irrelevant_subscribers_when_a_post_is_published(): void
    {
        Mail::fake();
        $website01 = Website::factory()->create();
        $website02 = Website::factory()->create();
        $relevantSubscription = Subscription::factory(5)
            ->create([
                'website_id' => $website01->getKey()
            ]);
        $irrelevantSubscription = Subscription::factory()->create([
            'website_id' => $website02->getKey(),
        ]);
        $post = Post::factory()->create([
            'website_id' => $website01->getKey(),
        ]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertSent(PostPublished::class, 5);
        foreach ($relevantSubscription as $subscription) {

            Mail::assertSent(PostPublished::class, function ($mail) use ($subscription) {
                return $mail->hasTo($subscription->email);
            });

            $this->assertDatabaseHas('sent_emails', [
                'subscription_id' => $subscription->id,
                'post_id' => $post->id,
            ]);
        }
        Mail::assertSent(PostPublished::class, function ($mail) use
        ($relevantSubscription) {
            return $mail->hasTo($relevantSubscription);
        });
        Mail::assertNotSent(PostPublished::class, function ($mail) use
        ($irrelevantSubscription) {
            return $mail->hasTo($irrelevantSubscription->email);
        });
    }

    #[Test]
    public function cannot_send_duplicate_emails_to_a_subscriber_when_a_post_is_published(): void
    {
        Mail::fake();
        $post = Post::factory()->create([
            'website_id' => $this->websiteId
        ]);
        $oldSubscription = Subscription::factory()->create([
            'website_id' => $this->websiteId,
            'email' => fn () => fake()->unique()->safeEmail(),
        ]);
        SentEmail::factory()->create([
            'subscription_id' => $oldSubscription->id,
            'post_id' => $post->id,
        ]);
        $newSubscription = Subscription::factory()->create([
            'website_id' => $this->websiteId,
            'email' => fn () => fake()->unique()->safeEmail(),
        ]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertSent(PostPublished::class, 3);
        Mail::assertSent(PostPublished::class, function ($mail) use ($newSubscription) {
            return $mail->hasTo($newSubscription->email);
        });
        $this->assertDatabaseHas('sent_emails', [
            'subscription_id' => $newSubscription->id,
            'post_id' => $post->id,
        ]);
    }
    #[Test]
    public function can_retry_failed_email_delivery(): void
    {
        $post = Post::factory()->create(['website_id' => $this->websiteId]);
        $subscription = Subscription::factory()->create(['website_id' => $this->websiteId]);
        Mail::fake();

        // Create a mock for PendingMail, set it to throw an exception the first time and then succeed
        $mock = $this
            ->mock(PendingMail::class, function (MockInterface $mock) {
            // Throw an exception for the first call
            $mock->shouldReceive('send')
                ->once()
                ->andThrow(new \Exception("Temporary failure"));

            // Successfully send the email on the second attempt
            $mock->shouldReceive('send')
                ->once()
                ->andReturnUsing(function () {
                    // Simulate actual sending here if needed
                });
        });

        $this->instance(PendingMail::class, $mock);
        try {
            $interactor = new PublishPostInteractor();
            $interactor->execute($post);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }

        // Assert that the email was sent (at least once)
        Mail::assertSent(PostPublished::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->email);
        });
    }
    #[Test]
    public function can_send_each_subscriber_only_one_email_per_post(): void
    {
        Mail::fake();
        $website = Website::factory()->create();
        Subscription::factory(10)->create(['website_id' => $website->id]);
        $post = Post::factory()->create(['website_id' => $website->id]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertSent(PostPublished::class, 10);
        $this->assertCount(10, SentEmail::where('post_id', $post->id)->get());
    }


}
