<?php

namespace App\Console\Commands;

use App\Jobs\SendPostEmails;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Console\Command;

class SendEmailsCommand extends Command
{
    protected $signature = 'send:emails {post_id}';
    protected $description = 'Send emails to all subscribers when a new post is published';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the post by the post_id argument
        $post = Post::find($this->argument('post_id'));  // Use the post_id passed to the command

        if (!$post) {
            $this->error('Post not found!');
            return;
        }

        // Get the subscribers for the website associated with the post
        $subscribers = Subscription::where('website_id', $post->website_id)->get();

        // Dispatch the email job for each subscriber
        foreach ($subscribers as $subscriber) {
            dispatch(new SendPostEmails($subscriber, $post));
        }
        $this->info('Emails sent successfully!');
    }
}

