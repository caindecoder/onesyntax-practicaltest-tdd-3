<?php

namespace Domain\Subscriptions\Interactors\Requests;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class CreateSubscriptionRequest extends Data
{
    #[MapInputName(('website_id'))]
    public string $websiteId;
    public string $email;

    public static function rules(): array
    {
        return [
            'website_id' => 'required|integer|exists:websites,id',
            'email' => 'required|string|email|unique:subscriptions,email',
        ];
    }
}
