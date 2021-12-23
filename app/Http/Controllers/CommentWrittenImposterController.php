<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Events\CommentWritten;

class CommentWrittenImposterController extends Controller
{
    public function store(User $user) #, int $amount
    {
        /*
        | Please Read:
        | For convenience I first made it possible to create multiple comments
        | with a parameter from route/web.php - comment/{amount} (comments as many as amount)
        | then I realized that I had to keep track of the latest achievement as generating many
        | comments could break the "exact match" condition (see AchievementUnlockedSubscriber)
        | and there is no way to not satisfy that equity if comments are created one-by-one
        | so I'm just leaving this seeder here on purpose to support multiple comment creating ability.
        */

        # generate as many comments as $amount (1)
        $comments = Comment::factory()->count(1)->create([
            'user_id' => $user->id,
        ])->each(function ($comment) {
            CommentWritten::dispatch($comment);
        });
    }
}
