<?php

namespace Requests;


class CreatePostRequest extends Data
{
    #[MapInputName('website_id')]

    public int $websiteId;
    public string $title;
    public string $description;


    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:posts',
            'description' => 'required|string',
            'website_id' => 'required|exists:websites,id',
        ];
    }
}
