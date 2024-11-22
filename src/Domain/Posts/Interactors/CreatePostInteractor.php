<?php

namespace Domain\Posts\Interactors;

use App\Models\Post;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePostInteractor
{
    public function execute(CreatePostRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $post = Post::query()->create([
                'title' => $request->title,
                'description' => $request->description,
                'website_id' => $request->websiteId,
            ]);
            Log::info('Post created: ', $post->toArray());
            app(PublishPostInteractor::class)->execute($post);
        });
    }
}
