<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // Permite que Eloquent guarde estos campos
    protected $fillable = ['user_id', 'photo_id'];

    // Relación: Un Like pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un Like pertenece a una Foto
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}