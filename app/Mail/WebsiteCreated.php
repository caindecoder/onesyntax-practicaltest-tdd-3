<?php

namespace App\Mail;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebsiteCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function build()
    {
        return $this->view('emails.websiteCreated')
            ->subject('New Website Created')
            ->with(['website' => $this->website]);
    }
}
