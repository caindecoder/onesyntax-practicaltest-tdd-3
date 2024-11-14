<?php

namespace Domain\Posts\Interactors;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Support\Facades\Mail;

class PublishPostInteractor
{
    public function execute(Post $post): void
    {
        $subscribers = Subscription::query()
            ->where('website_id', $post->website_id)
            ->get();


        foreach ($subscribers as $subscriber) {
            if (
                !SentEmail::query()
                ->where('post_id', $post->id)
                ->where('subscription_id', $subscriber->id)
                ->exists()
            ) {
                // Send the email
                Mail::to($subscriber->email)->send(new PostPublished($post));

                SentEmail::query()->create([
                    'post_id' => $post->id,
                    'subscription_id' => $subscriber->id,
                ]);
            }
        }
    }
}
