<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Posts\Interactors\PublishPostInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

        $this->withoutExceptionHandling();

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


        $publishPostInteractor = new PublishPostInteractor();
        $publishPostInteractor->execute($post);

        Mail::assertQueued(PostPublished::class, 1);

        foreach ($relevantSubscription as $subscription) {
            $this->assertDatabaseHas('sent_emails', [
                'subscription_id' => $relevantSubscription->id,
                'post_id' => $post->id,
            ]);
        }
    }

    #[Test]
    public function can_not_send_email_to_irrelevant_subscribers_when_a_post_is_published(): void
    {
        Mail::fake();

        $website01 = Website::factory()->create();
        $website02 = Website::factory()->create();
        $subscriber = Subscription::factory()->create([
            'website_id' => $website02->getKey(),
        ]);
        $post = Post::factory()->create([
            'website_id' => $website01->getKey(),
        ]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        $this->assertDatabaseMissing('sent_emails', [
            'subscription_id' => $subscriber->id,
            'post_id' => $post->id,
        ]);
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
        ]);
        SentEmail::factory()->create([
            'subscription_id' => $oldSubscription->id,
            'post_id' => $post->id,
        ]);
        $newSubscription = Subscription::factory()->create([
            'website_id' => $this->websiteId,
        ]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertQueued(PostPublished::class, 3);
        $this->assertDatabaseHas('sent_emails', [
            'subscription_id' => $newSubscription->id,
            'post_id' => $post->id,
        ]);
    }
}
