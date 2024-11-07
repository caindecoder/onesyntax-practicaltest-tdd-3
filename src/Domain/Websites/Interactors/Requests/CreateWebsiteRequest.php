<?php

namespace Domain\Websites\Interactors\Requests;

use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Support\Facades\Validator;


class CreateWebsiteRequest
{
    public string $name;
    public string $url;

    public function validate(): void
    {
        $validator = $this->processValidation();

        if ($this->websiteAlreadyExists()) {
            throw new ValidationException('A website with the same name or URL already exists.');
        }

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->first());
        }

    }

    protected function websiteAlreadyExists(): bool
    {
        return Website::query()
            ->where('name', $this->name)
            ->orWhere('url', $this->url)
            ->exists();
    }

    public function processValidation(): \Illuminate\Validation\Validator
    {
        return Validator::make(
            ['name' => $this->name, 'url' => $this->url],
            [
                'name' => 'required|string|max:255',
                'url' => 'required|url|max:255|unique:websites,url',
            ]
        );
    }
}
