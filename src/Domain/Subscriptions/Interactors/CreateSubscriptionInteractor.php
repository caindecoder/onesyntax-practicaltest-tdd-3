<?php

namespace Domain\Subscriptions\Interactors;

use App\Models\Subscription;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateSubscriptionInteractor
{
    public function execute(CreateSubscriptionRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $subscription = Subscription::query()->create([
                'website_id' => $request->websiteId,
                'email' => $request->email,
            ]);
            Log::info("Subscription Created: ", $subscription->toArray());
        });
    }
}
