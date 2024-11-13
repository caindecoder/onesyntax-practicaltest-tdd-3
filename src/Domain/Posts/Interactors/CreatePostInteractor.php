<?php

namespace Domain\Posts\Interactors;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Support\Facades\Mail;

class CreatePostInteractor extends \App\Models\Post
{
    public function execute(CreatePostRequest $request): Post
    {
        $request->validate();

        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->website_id = $request->website_id;
        $post->sent = false;
        $post->save();

        $this->postPublishedEmails($post);

        $post->sent = true;
        $post->save();

        return $post;
    }

    public function postPublishedEmails(Post $post): void
    {

        $subscribers = Subscription::where('website_id', $post->website_id)->get();

        foreach ($subscribers as $subscriber) {

            if (!SentEmail::where('post_id', $post->id)->where('subscription_id', $subscriber->id)->exists()) {
                Mail::to($subscriber->email)->send(new PostPublished($post));

                SentEmail::create([
                    'post_id' => $post->id,
                    'subscription_id' => $subscriber->id,
                ]);
            }
        }
    }
}
