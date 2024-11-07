<?php

namespace Domain\Emails\Interactors;

use App\Jobs\SendPostEmails;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\SentEmail;
use Domain\Emails\Interactors\Requests\SendEmailRequest;

class SendEmailInteractor
{
    public function sendEmails(Post $post): void
    {

        $subscribers = Subscription::whereDoesntHave('sentEmails', function ($query) use ($post) {
            $query->where('post_id', $post->id);
        })->get();

        foreach ($subscribers as $subscriber) {

            $request = new SendEmailRequest($subscriber->email, $post);

            SendPostEmails::dispatch($request->subscriberEmail, $request->post);

            SentEmail::create([
                'subscription_id' => $subscriber->id,
                'post_id' => $post->id,
            ]);
        }
    }
}
