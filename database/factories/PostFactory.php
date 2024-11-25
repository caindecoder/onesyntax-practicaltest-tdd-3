<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'website_id' => Website::factory(),
            'sent' => false,
        ];
    }
}
