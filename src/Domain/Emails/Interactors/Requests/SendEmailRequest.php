<?php

namespace Domain\Emails\Interactors\Requests;

use App\Models\Post;

class SendEmailRequest
{
    public string $subscriberEmail;
    public Post $post;

    public function __construct(string $subscriberEmail, Post $post)
    {
        $this->subscriberEmail = $subscriberEmail;
        $this->post = $post;
    }
}
