<?php

namespace Domain\Emails\Interactors;

use App\Jobs\SendPostEmails;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\SentEmail;
use App\Models\Website;
use Domain\Emails\Interactors\Requests\SendEmailRequest;

class SendEmailInteractor
{
    public function sendPostEmails(Post $post): void
    {

        $subscribers = Subscription::whereDoesntHave('sentEmails', function ($query) use ($post) {
            $query->where('post_id', $post->id);
        })->get();

        foreach ($subscribers as $subscriber) {

            SendPostEmails::dispatch($subscriber, $post);

            SentEmail::create([
                'subscription_id' => $subscriber->id,
                'post_id' => $post->id,
            ]);
        }
    }

    public function sendWebsiteEmails(Website $website): void
    {
        $subscribers = Subscription::whereDoesntHave(
            'sentEmails', function ($query) use ($website) {
                $query->where('website_id', $website->id);
        })->get();

        foreach ($subscribers as $subscriber) {

            SendPostEmails::dispatch($subscriber, $website);

            SentEmail::create([
                'subscription_id' => $subscriber->id,
                'post_id' => $website->id,
            ]);
        }
    }

    public function sendSubscriptionEmails(Subscription $subscription, Post $post, Website $website): void
    {
        $subscribers = Subscription::whereDoesntHave(
            'sentEmails', function ($query) use ($subscription) {
            $query->where('website_id', $subscription->id);
        })->get();

        foreach ($subscribers as $subscriber) {

            SendPostEmails::dispatch($subscriber, $subscription);

            SentEmail::create([
                'subscription_id' => $subscriber->id,
                'website_id' => $website->id,
                'post_id' => $post->id,
            ]);
        }
    }
}
