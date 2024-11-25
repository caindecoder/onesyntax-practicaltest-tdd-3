<?php

namespace Domain\Websites\Interactors\Requests;

use Spatie\LaravelData\Data;

class CreateWebsiteRequest extends Data
{
    public string $name;
    public string $url;

    public static function rules(): array
    {
        return [
            'name' => 'required|string|unique:websites,name',
            'url' => 'required|url|unique:websites,url',
        ];
    }
}
