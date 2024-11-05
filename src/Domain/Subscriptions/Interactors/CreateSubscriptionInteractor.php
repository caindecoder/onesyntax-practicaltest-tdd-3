<?php

namespace Domain\Subscriptions\Interactors;

use App\Models\Subscription;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;


class CreateSubscriptionInteractor
{
    public function create(CreateSubscriptionRequest $request): Subscription
    {
        $request->validate();

        $subscription = new Subscription();
        $subscription->email = $request->email;
        $subscription->website_id = $request->website_id;
        $subscription->save();

        return $subscription;
    }


}
