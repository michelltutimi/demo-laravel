<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['user_id', 'image_path', 'caption'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Para saber si el usuario autenticado ya le dio like
    public function isLikedBy(User $user = null)
    {
        return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }

    // Una foto tiene muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
