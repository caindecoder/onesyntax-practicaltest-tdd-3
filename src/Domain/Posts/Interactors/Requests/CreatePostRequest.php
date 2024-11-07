<?php

namespace Domain\Posts\Interactors\Requests;

use App\Models\Post;
use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreatePostRequest
{
    public string $title;
    public string $description;
    public int $website_id;

    /**
     * Validates the request data.
     *
     * @throws ValidationException
     */
    public function validate(): void
    {
        $validator = $this->processValidation();

        if ($this->postTitleAlreadyExists()) {
            throw new ValidationException('A post with the same title already exists.');
        }

        if (!$this->websiteExists()) {
            throw new ValidationException('The specified website does not exist.');
        }

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->first());
        }
    }

    protected function postTitleAlreadyExists(): bool
    {
        return Post::query()
            ->where('title', $this->title)
            ->exists();
    }

    private function websiteExists(): bool
    {
        return Website::query()
            ->where('id', $this->website_id)
            ->exists();
    }

    /**
     * @return void
     */
    public function processValidation(): \Illuminate\Validation\Validator
    {
        return Validator::make(
            [
                'title' => $this->title,
                'description' => $this->description,
                'website_id' => $this->website_id,
            ],
            [
                'title' => 'required|string|max:255|unique:posts,title',
                'description' => 'required|string|max:1000',
                'website_id' => ['required', 'integer', Rule::exists('websites', 'id')],
            ]
        );
    }
}
