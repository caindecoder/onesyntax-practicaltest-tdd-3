<?php

namespace Domain\Websites\Interactors;

use App\Models\Website;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;
use Illuminate\Support\Facades\DB;


class CreateWebsiteInteractor
{
    public function execute(CreateWebsiteRequest $request): void
    {
        DB::transaction(function () use ($request) {
            Website::query()->create([
                'name' => $request->name,
                'url' => $request->url,
            ]);
        });
    }


}

