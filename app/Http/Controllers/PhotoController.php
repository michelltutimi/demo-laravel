<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    // 1. Muestra la vista con el formulario para subir foto
    public function create()
    {
        return view('photos.create');
    }

    // 2. Procesa la imagen subida y la guarda
    public function store(Request $request)
    {
        // Validaciones básicas de la petición
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240', // Máximo 10 MB
            'caption' => 'nullable|string|max:1000',
        ]);

        // Guarda la foto físicamente en storage/app/public/photos
        $path = $request->file('image')->store('photos', 'public');

        // Crea el registro en MariaDB asociado al usuario que está logueado
        $photo = $request->user()->photos()->create([
            'image_path' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('photos.show', $photo)->with('status', '¡Foto publicada con éxito!');
    }

    public function show(Photo $photo)
    {
        // Carga los comentarios que NO tienen parent_id, junto con el autor y la cantidad de respuestas que tiene cada uno
        $photo->load(['user', 'comments' => function ($query) {
            $query->whereNull('parent_id')->with('user')->withCount('replies')->latest();
        }]);

        return view('photos.show', compact('photo'));
    }

    public function toggleLike(Photo $photo)
    {
        $userId = auth()->id(); // Obtiene el ID directo del usuario autenticado

        $like = $photo->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $photo->likes()->create([
                'user_id' => $userId
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $photo->likes()->count()
        ]);
    }

}