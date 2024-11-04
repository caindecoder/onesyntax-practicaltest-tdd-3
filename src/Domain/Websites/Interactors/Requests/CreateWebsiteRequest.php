<?php

namespace Domain\Websites\Interactors\Requests;

use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;


class CreateWebsiteRequest
{
    public string $name;

    public string $url;

    public function validate(): void
    {
        if (empty($this->name)) {
            throw new ValidationException('Website name is required.');
        }

        if (empty($this->url)) {
            throw new ValidationException('Website URL is required.');
        }

        if ($this->websiteAlreadyExists()) {
            throw new ValidationException('A website with the same name or URL already exists.');
        }
    }

    protected function websiteAlreadyExists(): bool
    {
        return Website::query()
            ->where('name', $this->name)
            ->orWhere('url', $this->url)
            ->exists();
    }
}
