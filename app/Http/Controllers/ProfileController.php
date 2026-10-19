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

    // Show the form for editing the authenticated user's profile.
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // Update the authenticated user's profile information.
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate form input (ignore current user ID for username uniqueness check)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
        ]);

        // Update user fields
        $user->update([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'bio' => $validated['bio'],
        ]);

        return redirect()->route('profile.show', $user->username)
                         ->with('status', 'Profile updated successfully!');
    }
}