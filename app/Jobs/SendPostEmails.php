<?php

namespace App\Jobs;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostEmails
{
    use Dispatchable, Queueable, SerializesModels;

    public $subscriberEmail;
    public $post;

    public function __construct($subscriberEmail, Post $post)
    {
        $this->subscriberEmail = $subscriberEmail;
        $this->post = $post;
    }

    public function handle()
    {
        Mail::to($this->subscriberEmail)->send(new PostPublished($this->post));
    }

}
