<?php

namespace Domain\Subscriptions\Interactors\Requests;

use AllowDynamicProperties;
use App\Models\Subscription;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Support\Facades\Validator;


 class CreateSubscriptionRequest
{
    public string $email;
    public string $website_id;

    public function validate(): void
    {
        $validator = $this->processValidation();

        if ($this->emailAlreadyExists()) {
            throw new ValidationException('Subscription already exists for this email and website.');
        }

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->first());
        }

    }

    protected function emailAlreadyExists(): bool
    {
        return Subscription::query()
            ->where('email', $this->email)
            ->orWhere('website_id', $this->website_id)
            ->exists();
    }

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function processValidation(): \Illuminate\Validation\Validator
    {
        return Validator::make(
            [
                'email' => $this->email,
                'website_id' => $this->website_id,
            ],
            [
                'email' => ['required', 'email', 'unique:subscriptions,email'],
                'website_id' => 'required|exists:websites,id',
            ]
        );
    }

}
