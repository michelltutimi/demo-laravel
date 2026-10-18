<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['user_id', 'image_path', 'caption'];

    // Get the user who owns the photo
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get all likes for the photo
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Check if the given user has liked this photo
    public function isLikedBy(?User $user = null)
    {
        return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }

    // Get all the comments for the photo
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
