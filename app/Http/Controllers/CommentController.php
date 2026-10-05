<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = $photo->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id ?? null,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comentario publicado',
            'comment' => $comment
        ], 201);
    }

    // Obtener las respuestas de un comentario específico (Lazy Loading)
    public function indexReplies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->oldest() // Muestra las respuestas en orden cronológico
            ->get();

        return response()->json($replies);
    }
}