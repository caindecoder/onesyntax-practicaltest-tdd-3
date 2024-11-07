<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SubscriberReceivesEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $website = Website::create(['name' => 'Test Website', 'url' => 'https://test.com']);
        Subscription::create(['email' => 'subscriber@example.com', 'website_id' => $website->id]);
    }

    #[Test]
    public function can_only_valid_subscribers_receive_email()
    {

    }

    #[Test]
    public function can_send_emails_to_each_subscription()
    {

    }

    #[Test]
    public function can_only_send_one_email_to_each_subscriber()
    {

    }

    #[Test]
    public function can_user_receive_email_after_subscribing_to_website()
    {

    }

    #[Test]
    public function can_send_email_to_all_subscribers_when_new_post_is_published()
    {

    }

    #[Test]
    public function can_use_commands_to_send_emails_to_subscribers()
    {

    }
    #[Test]
    public function can_use_queues_to_schedule_sending_in_the_background()
    {

    }
}
