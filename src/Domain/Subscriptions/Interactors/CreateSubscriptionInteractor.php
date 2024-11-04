<?php


use App\Models\Subscription;
use App\Models\Website;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Domain\ValidationExceptions\ValidationException;

class CreateSubscriptionInteractor
{
    public function create(CreateSubscriptionRequest $request): Subscription
    {
        $request->validate();

        $subscription = new Subscription();
        $subscription->email = $request->email;
        $subscription->website_id = $request->website_ID;
        $subscription->save();
        return $subscription;
    }


}
