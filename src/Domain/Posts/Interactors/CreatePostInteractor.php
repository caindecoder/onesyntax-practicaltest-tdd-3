<?php

namespace Domain\Posts\Interactors;

use App\Models\Post;
use Domain\Posts\Interactors\Requests\CreatePostRequest;

class CreatePostInteractor
{
    public function create(CreatePostRequest $request): Post
    {
        // Perform validation
        $request->validate();

        // Create and save the post
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return $post;
    }
}
