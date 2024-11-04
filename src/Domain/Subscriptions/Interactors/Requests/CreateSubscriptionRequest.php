<?php

namespace Domain\Subscriptions\Interactors\Requests;

use App\Models\Subscription;
use Domain\ValidationExceptions\ValidationException;


class CreateSubscriptionRequest
{
    public string $email;
    public string $website_ID;

    public function validate(): void
    {
        if (empty($this->email)) {
            throw new ValidationException('eMail is required.');
        }

        if (empty($this->website_ID)) {
            throw new ValidationException('Website is required.');
        }

        if ($this->emailAlreadyExists()) {
            throw new ValidationException('eMail already exists.');
        }

    }

    protected function emailAlreadyExists(): bool
    {
        return Subscription::query()
            ->where('email', $this->email)
            ->orWhere('website_ID', $this->website_ID)
            ->exists();
    }

}
