<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MixedBehaviourTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test if a new user can be created
     *
     * @return void
     */
    public function test_creating_user()
    {
        $user = $this->createUser();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * Create one lesson, watch it and
     * create one comment and expect the right result
     *
     * @return void
     */
    public function test_watching_one_lesson_and_creating_one_comment()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 1);
        $comments = $this->createWrittenComment($user, 1);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJson([
                'unlocked_achievements' => [
                    'First Comment Written',
                    'First Lesson Watched',
                ],
            ])
            ->assertJson([
                'next_available_achievements' => [
                    '3 Comments Written',
                    '5 Lessons Watched',
                ]
            ])
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 2);
    }

    /**
     * Create 5 lesson, watch them and
     * create 5 comments and expect the right result
     *
     * @return void
     */
    public function test_watching_five_lesson_and_creating_five_comments()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 5);
        $comments = $this->createWrittenComment($user, 5);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJson([
                'unlocked_achievements' => [
                    'First Comment Written',
                    '3 Comments Written',
                    '5 Comments Written',
                    'First Lesson Watched',
                    '5 Lessons Watched'
                ],
            ])
            ->assertJson([
                'next_available_achievements' => [
                    '10 Comments Written',
                    '10 Lessons Watched',
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 50 lesson, watch them and
     * create 20 comments and expect the right result
     *
     * @return void
     */
    public function test_watching_fifty_lesson_and_creating_twenty_comments()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 50);
        $comments = $this->createWrittenComment($user, 20);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJson([
                'unlocked_achievements' => [
                    'First Comment Written',
                    '3 Comments Written',
                    '5 Comments Written',
                    '10 Comments Written',
                    '20 Comments Written',
                    'First Lesson Watched',
                    '5 Lessons Watched',
                    '10 Lessons Watched',
                    '25 Lessons Watched',
                    '50 Lessons Watched',
                ],
            ])
            ->assertJson([
                'next_available_achievements' => []
            ])
            ->assertJsonPath('current_badge', 'master')
            ->assertJsonPath('next_badge', null)
            ->assertJsonPath('remaining_to_unlock_next_badge', 0);
    }

    /**
     * Create no lesson and
     * create 3 comments and expect the right result
     *
     * @return void
     */
    public function test_watching_no_lesson_and_creating_three_comments()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 3);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJson([
                'unlocked_achievements' => [
                    'First Comment Written',
                    '3 Comments Written',
                ],
            ])
            ->assertJson([
                'next_available_achievements' => [
                    '5 Comments Written',
                    'First Lesson Watched',
                ]
            ])
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 2);
    }

    /**
     * Create 51 lessons, watch them and
     * create no comments and expect the right result
     *
     * @return void
     */
    public function test_watching_fiftyone_lesson_and_creating_no_comments()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 51);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJson([
                'unlocked_achievements' => [
                    'First Lesson Watched',
                    '5 Lessons Watched',
                    '10 Lessons Watched',
                    '25 Lessons Watched',
                    '50 Lessons Watched',
                ],
            ])
            ->assertJson([
                'next_available_achievements' => [
                    'First Comment Written',
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }
}
