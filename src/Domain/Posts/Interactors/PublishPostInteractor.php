<?php

namespace Domain\Posts\Interactors;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublishPostInteractor
{
    public function execute(Post $post): void
    {
        $subscribers = $this->getSubscribers($post);
        $sentEmails = [];
        $this->sendEmail($subscribers, $post, $sentEmails);
    }

    public function getSubscribers(Post $post)
    {
        $subscribers = Subscription::where('website_id', $post->website_id)
            ->whereDoesntHave('sentEmails', function ($query) use ($post) {
                $query->where('post_id', $post->id);
            })
            ->get();
        return $subscribers;
    }

    public function sendEmail($subscribers, Post $post,  array $sentEmails): void
    {
        foreach ($subscribers as $subscriber) {
            $emailSent = $this->sendEmailWithRetry( $subscriber, $post);
            if ($emailSent) {
                $sentEmails[] = [
                    'post_id' => $post->id,
                    'subscription_id' => $subscriber->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($sentEmails)) {
            SentEmail::insert($sentEmails);
        }
    }

    private function sendEmailWithRetry(Subscription $subscriber, Post $post ): bool
    {
        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                Mail::to($subscriber->email)
                    ->send(new PostPublished($post));
                $this
                    ->logEmailSent($subscriber, $post);
                return true;
            } catch (\Exception $e) {
                Log::error(
                    'Failed to send email to ' . $subscriber->email .
                    ' for post ID: ' . $post->id .
                    ' on attempt ' . ($retryCount + 1) . ': ' . $e->getMessage());
            }
            $retryCount++;
        }
        Log::error(
            'Failed to send email to ' . $subscriber->email .
            ' after ' . $maxRetries .
            ' attempts for post ID: ' . $post->id);
        return false;
    }

    private function logEmailSent(Subscription $subscriber, Post $post)
    {
        Log::info('Email sent to ' . $subscriber->email . ' for post ID: ' . $post->id);
    }
}
