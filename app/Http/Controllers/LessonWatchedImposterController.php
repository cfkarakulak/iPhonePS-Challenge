<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\LessonWatched;
use Illuminate\Http\Request;

class LessonWatchedImposterController extends Controller
{
    public function store(User $user, int $amount)
    {
        LessonWatched::dispatch($user);
    }
}
