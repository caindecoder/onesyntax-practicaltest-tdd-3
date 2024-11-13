<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionAdded extends Mailable
{
    use Queueable, SerializesModels;

    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject('New Subscription Added: ' . $this->subscription->title)
            ->view('emails.subscriptionAdded');
    }

}
