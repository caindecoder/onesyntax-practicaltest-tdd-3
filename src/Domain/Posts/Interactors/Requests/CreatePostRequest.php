<?php

namespace Domain\Posts\Interactors\Requests;

use App\Models\Post;
use Domain\ValidationExceptions\ValidationException;


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
        if (empty($this->title)) {
            throw new ValidationException('Post title is required.');
        }

        if (empty($this->description)) {
            throw new ValidationException('Post description is required.');
        }

        if ($this->postTitleAlreadyExists()) {
            throw new ValidationException('A post with the same title already exists.');
        }
    }

    protected function postTitleAlreadyExists(): bool
    {
        return Post::query()
            ->where('title', $this->title)
            ->exists();
    }
}
