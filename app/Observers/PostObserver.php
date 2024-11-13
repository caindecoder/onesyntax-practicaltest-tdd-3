<?php

namespace App\Observers;

use App\Models\Post;
use App\Jobs\SendPostEmails;
use App\Models\SentEmail;
use App\Models\Subscription;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post): void
    {

    }
}
