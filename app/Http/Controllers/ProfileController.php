<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Display the specified user's profile with photo metrics and follow counts.
    public function show(User $user = null)
    {
        // Fallback to the authenticated user if no route key is specified
        $user = $user ?? auth()->user();

        // Precalculate photo counts, engagement metrics, and follow metrics
        $user->load(['photos' => function ($query) {
            $query->withCount(['likes', 'comments'])->latest();
        }])->loadCount(['followers', 'following']);

        return view('profile.show', compact('user'));
    }
}