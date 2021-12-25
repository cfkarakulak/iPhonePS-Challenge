<?php

namespace Tests;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Comment;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUser()
    {
        return User::factory()->create();
    }

    public function createWatchedLesson(User $user, int $count)
    {
        return Lesson::factory()->count($count)->create()->each(
            function ($lesson) use ($user) {
                $user->lessons()->attach($lesson->id, ['watched' => 1]);
            }
        );
    }

    public function createWrittenComment(User $user, int $count)
    {
        return Comment::factory()->count($count)->create([
            'user_id' => $user->id,
        ]);
    }
}
