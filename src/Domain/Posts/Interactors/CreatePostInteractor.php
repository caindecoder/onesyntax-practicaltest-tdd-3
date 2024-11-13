<?php

namespace Domain\Posts\Interactors;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Support\Facades\Mail;

class CreatePostInteractor
{
    public function __construct(protected PublishPostInteractor $publishPostInteractor)
    {
    }

    public function execute(CreatePostRequest $request): Post
    {
        $request->validate();

        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->website_id = $request->website_id;
        $post->sent = false;
        $post->save();

        //dependency inject
        $this->publishPostInteractor->execute($post);

        $post->sent = true;
        $post->save();

        return $post;
    }
}
