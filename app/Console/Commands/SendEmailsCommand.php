<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmailsCommand extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Send emails to subscribers about new posts.';

    public function handle()
    {
        // Logic to fetch new posts and send emails to subscribers
        // Use the SendEmailInteractor to handle sending logic
    }
}
