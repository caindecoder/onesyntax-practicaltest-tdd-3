<?php

namespace App\Console\Commands;

use App\Jobs\SendPostEmails;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Console\Command;

class SendEmailsCommand extends Command
{
    protected $signature = 'send:emails';
    protected $description = 'Send emails to all subscribers when a new post is published';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $post = Post::latest()->first();
        $subscribers = Subscription::all();

        foreach ($subscribers as $subscriber) {
            SendPostEmails::dispatch($subscriber->email, $post);
        }

        $this->info('Emails sent successfully!');
    }
}

