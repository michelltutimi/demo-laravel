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
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/photos/create', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');

    Route::get('/photos/{photo}', [PhotoController::class, 'show'])->name('photos.show');
    Route::post('/photos/{photo}/like', [PhotoController::class, 'toggleLike'])->name('photos.like');
    
    Route::post('/photos/{photo}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/replies', [CommentController::class, 'indexReplies'])->name('comments.replies');
});