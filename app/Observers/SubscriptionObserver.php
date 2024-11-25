<?php


namespace App\Observers;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;

class SubscriptionObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param Post $post
     * @return void
     */
    public function created(Subscription $subscription, Website $website): void
    {

    }
}
