<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\CommentWritten;
use Illuminate\Http\Request;

class CommentWrittenImposterController extends Controller
{
    public function store(User $user, int $amount)
    {
        CommentWritten::dispatch($user);
    }
}
