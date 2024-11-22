<?php

namespace Domain\Posts\Interactors;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class PublishPostInteractor
{
    public function execute(Post $post): void
    {
        $subscribers = $this->getSubscribers($post);
        $this->sendEmail($subscribers, $post);
    }

    public function getSubscribers(Post $post)
    {
       return Subscription::where('website_id', $post->website_id)
            ->whereDoesntHave('sentEmails', function ($query) use ($post) {
                $query->where('post_id', $post->id);
            })
            ->get();
    }
    public function sendEmail($subscribers, Post $post): void
    {
        $subscribers->each(function ($subscriber) use ($post) {
            SentEmail::query()->create([
                'subscription_id' => $subscriber->id,
                'post_id' => $post->id,
            ]);
            Mail::to($subscriber->email)->queue(new PostPublished($post));
        });
    }
}
