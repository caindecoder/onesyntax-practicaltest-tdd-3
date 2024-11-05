<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if it follows Laravel's naming conventions)
    protected $table = 'posts';

    // Fields that are mass assignable
    protected $fillable = [
        'title',
        'description',
        'website_id', // Add this to allow mass assignment of website_id
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
