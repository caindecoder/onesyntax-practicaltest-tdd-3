<?php

namespace Domain\Posts\Interactors;

use App\Models\Post;
use Domain\Posts\Interactors\Requests\CreatePostRequest;

class CreatePostInteractor
{
    public function create(CreatePostRequest $request): Post
    {
        $request->validate();

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->website_id = $request->website_id;
        $post->save();

        return $post;
    }
}
