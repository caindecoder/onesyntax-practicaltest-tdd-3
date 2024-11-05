<?php

namespace App\Jobs;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        $subscriptions = Subscription::where('website_id', $this->post->website_id)->get();

        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->email)->send(new PostPublished($this->post));
        }
    }
}
