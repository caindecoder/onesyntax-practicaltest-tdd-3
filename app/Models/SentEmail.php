<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
