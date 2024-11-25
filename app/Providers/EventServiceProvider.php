<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use App\Observers\PostObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\WebsiteObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Website::observe(WebsiteObserver::class);
        Post::observe(PostObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}
