<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'website_id' => \App\Models\Website::factory(),
        ];
    }
}
