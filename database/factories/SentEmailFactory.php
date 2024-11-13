<?php

namespace Database\Factories;

use App\Models\SentEmail;
use App\Models\Subscription;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class SentEmailFactory extends Factory
{
    protected $model = SentEmail::class;

    public function definition()
    {
        return [
            'subscription_id' => Subscription::factory(),  // Automatically create a Subscription
            'post_id' => Post::factory(),                    // Automatically create a Post
        ];
    }
}
