<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;

class LessonWatchedImposterController extends Controller
{
    public function store(User $user) #, int $amount
    {
        /*
        | Please Read:
        | For convenience I first made it possible to create multiple lessons watched
        | with a parameter from route/web.php - lesson/{amount} (lessons watched as many as amount)
        | then I realized that I had to keep track of the latest achievement as generating many
        | lessons watched could break the "exact match" condition (see AchievementUnlockedSubscriber)
        | and there is no way to not satisfy that equity if lessons watched are created one-by-one
        | so I'm just leaving this seeder here on purpose to support multiple lesson watched creating ability.
        */

        # generate as many lessons watched as $amount (1)
        $lessons = Lesson::factory()->count(1)->create()->each(
            function ($lesson) use ($user) {
                LessonWatched::dispatch($lesson, $user);
            }
        );
    }
}
