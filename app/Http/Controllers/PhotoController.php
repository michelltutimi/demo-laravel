<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    // The view with the form for creating a new photo
    public function create()
    {
        return view('photos.create');
    }

    // Store the uploaded photo and its data in the database
    public function store(Request $request)
    {
        // Basic validation for the uploaded image and optional caption
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB max
            'caption' => 'nullable|string|max:1000',
        ]);

        // Save the original photo in storage/app/public/photos
        $path = $request->file('image')->store('photos', 'public');

        // Create a new photo record in the database associated with the authenticated user
        $photo = $request->user()->photos()->create([
            'image_path' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('photos.show', $photo)->with('status', '¡Pic uploaded successfully!');
    }

    // Show a specific photo along with its comments and related data
    public function show(Photo $photo)
    {
        // Load comments that do NOT have a parent_id, along with their author and the number of replies each one has
        $photo->load(['user', 'comments' => function ($query) {
            $query->whereNull('parent_id')->with('user')->withCount('replies')->latest();
        }]);

        return view('photos.show', compact('photo'));
    }

    // Toggle like for a specific photo
    public function toggleLike(Photo $photo)
    {
        $userId = auth()->id(); // Get the authenticated user's ID

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