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
    }

    // USER PUBLISHES POST TESTS

    #[Test]
    public function can_send_email_to_all_subscribers_when_a_post_is_published(): void
    {
        Mail::fake();
        Subscription::factory(5)
            ->create([
                'website_id' => $this->websiteId
            ]);

        $post = Post::factory()->create([
            'website_id' => $this->websiteId
        ]);

        $interactor = new PublishPostInteractor();
        $interactor->execute($post);

        Mail::assertSent(PostPublished::class, 5);
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

        Mail::assertSent(PostPublished::class, 1);
        Mail::assertSent(PostPublished::class, function ($mail) use ($newSubscription) {
            return $mail->hasTo($newSubscription->email);
        });
        $this->assertDatabaseHas('sent_emails', [
            'subscription_id' => $newSubscription->id,
            'post_id' => $post->id,
        ]);
    }

}
