<?php

namespace Domain\Subscriptions\Interactors\Requests;

use App\Models\Subscription;
use Domain\ValidationExceptions\ValidationException;


class CreateSubscriptionRequest
{
    public string $email;
    public string $website_id;

    public function validate(): void
    {
        if (empty($this->email) || empty($this->website_id)) {
            throw new ValidationException('Email and Website ID are required.');
        }

        if ($this->emailAlreadyExists()) {
            throw new ValidationException('Subscription already exists for this email and website.');
        }

    }

    protected function emailAlreadyExists(): bool
    {
        return Subscription::query()
            ->where('email', $this->email)
            ->orWhere('website_id', $this->website_id)
            ->exists();
    }

}
