<?php
namespace App\Observers;

use App\Models\Website;
use App\Mail\WebsiteCreated; // Mailable class to handle email content
use Illuminate\Support\Facades\Mail;

class WebsiteObserver
{
    public function created(Website $website)
    {
        // Send email when a new website is created
        Mail::to('admin@example.com')->send(new WebsiteCreated($website));
    }
}
