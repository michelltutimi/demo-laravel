<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    // Display the chronological photo feed.
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return view('home');
        }

        $authUser = auth()->user();
        $followingIds = $authUser->following()->pluck('users.id');

        // Base query with precalculated relations, counts, and logged-in user's like status
        $baseQuery = Photo::query()
            ->with('user')
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function ($q) use ($authUser) {
                $q->where('user_id', $authUser->id);
            }]);

        $photos = null;

        // Step 1: Try fetching photos from followed users if following list is not empty
        if ($followingIds->isNotEmpty()) {
            $photos = $baseQuery->clone()
                ->whereIn('user_id', $followingIds)
                ->latest('id')
                ->cursorPaginate(10);
        }

        // Step 2: Fallback if user follows nobody OR followed users have zero photos
        if (!$photos || $photos->isEmpty()) {
            $latestPhotoIds = Photo::selectRaw('MAX(id) as id')
                ->groupBy('user_id')
                ->latest('id')
                ->take(10)
                ->pluck('id');

            $photos = $baseQuery->clone()
                ->whereIn('id', $latestPhotoIds)
                ->latest('id')
                ->cursorPaginate(10);
        }

        // Return JSON payload for dynamic AJAX infinite scroll
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $photos->items(),
                'next_cursor' => $photos->nextCursor()?->encode(),
                'has_more' => $photos->hasMorePages(),
            ]);
        }

        return view('feed.index', compact('photos'));
    }
}