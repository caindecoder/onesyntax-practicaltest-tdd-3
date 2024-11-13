<?php

namespace Domain\Subscriptions\Interactors;

use App\Mail\PostPublished;
use App\Mail\SubscriptionAdded;
use App\Models\Post;
use App\Models\Subscription;
use App\Observers\PostObserver;
use App\Observers\SubscriptionObserver;
use Domain\Emails\Interactors\SendEmailInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Illuminate\Support\Facades\Mail;


class CreateSubscriptionInteractor
{
    public function execute(CreateSubscriptionRequest $request): Subscription
    {
        $request->validate();

        $subscription = new Subscription();
        $subscription->email = $request->email;
        $subscription->website_id = $request->website_id;
        $subscription->save();

        $this->subscriptionAddedEmails($subscription);

        return $subscription;
    }

    public function subscriptionAddedEmails(Subscription $subscription): void
    {
        $subscribers = Subscription::query()
            ->where('website_id', $subscription->website_id)
            ->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new SubscriptionAdded($subscription));
        }
    }


}
