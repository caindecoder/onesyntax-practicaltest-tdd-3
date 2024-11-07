<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'post_id'];

    // Define a relationship to Subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Define a relationship to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
