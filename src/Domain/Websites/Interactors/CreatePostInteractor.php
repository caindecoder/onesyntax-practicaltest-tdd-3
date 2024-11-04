<?php

namespace Domain\Websites\Interactors;


use Illuminate\Support\Facades\DB;
use Requests\CreatePostRequest;

class CreatePostInteractor
{
    public function execute(CreatePostRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $post = Post::query()->create([
                'title' => $request->title,
                'description' => $request->description,
                'website_id' => $request->website_id,
            ]);
            $this->sendEmails($post);
        });

    }

    public function sendEmails(Website $website): void
    {
        $sendEmailInteractor = new SendEmailInteractor();
        $sendEmailInteractor->execute($website);
    }

}
