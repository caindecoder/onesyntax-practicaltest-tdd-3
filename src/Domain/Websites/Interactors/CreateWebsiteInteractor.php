<?php

namespace Domain\Websites\Interactors;

use App\Models\Website;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;

class CreateWebsiteInteractor
{
    public function create(CreateWebsiteRequest $request): Website
    {
        $request->validate();

        $website = new Website;
        $website->name = $request->name;
        $website->url = $request->url;

        $website->save();

        return $website;
    }
}

