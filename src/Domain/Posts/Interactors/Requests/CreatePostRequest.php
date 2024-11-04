<?php

namespace Domain\Posts\Interactors\Requests;

use App\Models\Post;
use Domain\ValidationExceptions\ValidationException;


class CreatePostRequest
{
    public string $title;
    public string $description;

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
    }
}
