<?php

namespace Domain\Posts\Interactors\Requests;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class CreatePostRequest extends Data
{
    #[MapInputName(('website_id'))]
    public string $websiteId;
    public string $title;
    public string $description;

   public static function rules(): array
   {
       return [
           'website_id' => ['required', 'exists:websites,id'],
           'title' => ['required', 'string', 'unique:posts,title'],
           'description' => ['required', 'string'],
       ];
   }
}
