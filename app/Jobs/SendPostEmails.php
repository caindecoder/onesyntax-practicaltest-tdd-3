<?php

namespace App\Jobs;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostEmails implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $subscriber;
    public $post;

    public function __construct(Subscription $subscriber, Post $post)
    {
        $this->subscriber = $subscriber;
        $this->post = $post;
    }

    public function handle()
    {
        $sentPosts = $this->subscriber->sent_posts ?? [];

        // Check if the post_id is already in the sent list
        if (!$this->hasAlreadySent($this->subscriber->email, $this->post->id)) {
            // Send email
            Mail::to($this->subscriber->email)->send(new PostPublished($this->post));
            $this->markAsSent($this->subscriber->email, $this->post->id);
            // Update the sent_posts JSON field
            $sentPosts[] = $this->post->id;
            $this->subscriber->update(['sent_posts' => $sentPosts]);
        }
    }

    protected function hasAlreadySent($email, $postId)
    {
        // Check if the email has already been sent for this post
        return SentEmail::where('email', $email)->where('post_id', $postId)->exists();
    }

    protected function markAsSent($email, $postId)
    {
        // Log the sent email to prevent duplicates
        SentEmail::create(['email' => $email, 'post_id' => $postId]);
    }
}
