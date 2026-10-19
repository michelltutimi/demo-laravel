<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    // Toggle follow/unfollow status for the specified user.
    public function toggle(User $user)
    {
        $authUser = auth()->user();

        // Prevent users from following themselves
        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'Cannot follow yourself'], 400);
        }

        // Toggle relation in the pivot table
        $changes = $authUser->following()->toggle($user->id);
        $isFollowing = count($changes['attached']) > 0;

        return response()->json([
            'following' => $isFollowing,
            'followers_count' => $user->followers()->count()
        ]);
    }
}