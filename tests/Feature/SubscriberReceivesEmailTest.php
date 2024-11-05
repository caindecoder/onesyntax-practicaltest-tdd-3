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
        // Set up initial data
        $website = Website::create(['name' => 'Test Website', 'url' => 'https://test.com']);
        Subscription::create(['email' => 'subscriber@example.com', 'website_id' => $website->id]);
    }

    #[Test]
    public function can_user_receive_email_after_subscribing_to_website()
    {

    }
}
