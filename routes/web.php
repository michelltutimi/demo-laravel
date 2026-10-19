<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FeedController;

// Root URL handles both Guest Landing and Authenticated Feed
Route::get('/', [FeedController::class, 'index'])->name('feed');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Logout only for authenticated users
    Route::post('/logout', [AuthController::class, 'logout'], )->name('logout');

    // Create a photo
    Route::get('/photos/create', [PhotoController::class, 'create'])->name('photos.create');
    // Store the photo file and data
    Route::post('/photos/store', [PhotoController::class, 'store'])->name('photos.store');
    // Show a photo with its data and more
    Route::get('/photos/{photo}', [PhotoController::class, 'show'])->name('photos.show');

    // Toggle like for a photo
    Route::post('/photos/{photo}/like', [PhotoController::class, 'toggleLike'])->name('photos.like');
    // Store a comment for a photo
    Route::post('/photos/{photo}/comments', [CommentController::class, 'store'])->name('comments.store');
    // Load replies for a comment
    Route::get('/comments/{comment}/replies', [CommentController::class, 'indexReplies'])->name('comments.replies');

    // Edit profile routes (Must be defined BEFORE dynamic username parameter)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    // User profile (Own route or to view other users)
    // using user:username route model binding to fetch the user by username instead of ID
    Route::get('/profile/{user:username?}', [ProfileController::class, 'show'])->name('profile.show');
    // Follow / Unfollow user action
    Route::post('/users/{user:username}/follow', [FollowController::class, 'toggle'])->name('users.follow');
});