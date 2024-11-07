<?php

namespace Tests\Feature;

use App\Jobs\SendPostEmails;
use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use App\Models\User;
use Domain\Emails\Interactors\SendEmailInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SubscriberReceivesEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $website = Website::factory()->create();

        $post = Post::factory()->create(['website_id' => $website->id]);

        Subscription::factory()->create(['email' => 'user@example.com', 'website_id' => $website->id]);
        Subscription::factory()->create(['email' => 'user2@example.com', 'website_id' => $website->id]);

        $this->post = $post;

        $this->assertDatabaseHas('websites', ['id' => $website->id]);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('subscriptions', ['email' => 'user@example.com']);
    }

    #[Test]
    public function can_only_valid_subscribers_receive_email(): void
    {
        Mail::fake();

        $interactor = new SendEmailInteractor();
        $interactor->sendEmails($this->post);

        Mail::assertSent(PostPublished::class, function ($mail) {
            return $mail->hasTo('user@example.com') || $mail->hasTo('user2@example.com');
        });

        Mail::assertSent(PostPublished::class, 2);
    }

    #[Test]
    public function can_send_emails_to_each_subscription(): void
    {
        Mail::fake();

        $post = Post::first();
        $subscribers = Subscription::all();
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostPublished($post));
        }

        Mail::assertSent(PostPublished::class, 2);
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
    public function can_user_receive_email_after_subscribing_to_website(): void
    {
        Mail::fake();

        $website = Website::first();
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user3@example.com',
            'password' => 'password',
        ]);
        Subscription::create([
            'email' => $user->email,
            'website_id' => $website->id,
        ]);

        $post = Post::first();
        Mail::to($user->email)->send(new PostPublished($post));

        Mail::assertSent(PostPublished::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    #[Test]
    public function can_send_email_to_all_subscribers_when_new_post_is_published(): void
    {
        Mail::fake();

        $post = Post::first();
        $subscribers = Subscription::all();
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostPublished($post));
        }

        Mail::assertSent(PostPublished::class, 2);
    }

    #[Test]
    public function can_use_commands_to_send_emails_to_subscribers(): void
    {
        Mail::fake();

        $this->artisan('send:emails')
        ->assertExitCode(0);

        Mail::assertSent(PostPublished::class);
    }

    #[Test]
    public function can_use_queues_to_schedule_sending_in_the_background(): void
    {
        Queue::fake();

        $website = Website::factory()->create();
        $post = Post::factory()->create(['website_id' => $website->id]);
        $subscribers = Subscription::factory()->count(2)->create(['website_id' => $website->id]);

        foreach ($subscribers as $subscriber) {
            dispatch(new SendPostEmails($subscriber->email, $post));
        }

        Queue::assertPushed(SendPostEmails::class, 2);
    }


}
