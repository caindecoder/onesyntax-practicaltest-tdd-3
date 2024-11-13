<?php

namespace Domain\Websites\Interactors;

use App\Mail\PostPublished;
use App\Mail\WebsiteCreated;
use App\Models\Subscription;
use App\Models\Website;
use App\Observers\WebsiteObserver;
use Domain\Emails\Interactors\SendEmailInteractor;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;
use Illuminate\Support\Facades\Mail;

class CreateWebsiteInteractor
{
    public function execute(CreateWebsiteRequest $request): Website
    {
        $request->validate();

        $website = new Website;
        $website->name = $request->name;
        $website->url = $request->url;

        $website->save();

        event(new WebsiteCreated($website));
        (new \App\Observers\WebsiteObserver)->created($website);
        (new SendEmailInteractor($website))->sendWebsiteEmails($website);

        return $website;
    }


}

