<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('home');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Logout only for authenticated users
    Route::post('/logout', [AuthController::class, 'logout']);
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
});