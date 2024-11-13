<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Website;
use App\Observers\PostObserver;
use App\Observers\WebsiteObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Website::observe(WebsiteObserver::class);
        Post::observe(PostObserver::class);
    }
}
