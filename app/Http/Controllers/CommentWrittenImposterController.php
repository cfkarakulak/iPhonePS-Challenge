<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Events\CommentWritten;

class CommentWrittenImposterController extends Controller
{
    public function store(User $user)
    {
        # generate placeholder comments
        $comments = Comment::factory()->count(1)->create([
            'user_id' => $user->id,
        ])->each(function ($comment) {
            return event(
                new CommentWritten($comment)
            );
        });

        return response()->json([
            'success' => true,
            'count' => $user->comments->count(),
        ], 201);
    }
}
