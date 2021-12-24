<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;

class LessonWatchedImposterController extends Controller
{
    public function store(User $user)
    {
        # generate placeholder lessons
        $lessons = Lesson::factory()->count(1)->create()->each(
            function ($lesson) use ($user) {
                # assume the lesson is watched
                $user->lessons()->attach($lesson->id, ['watched' => 1]);

                return event(
                    new LessonWatched($lesson, $user)
                );
            }
        );
    }
}
