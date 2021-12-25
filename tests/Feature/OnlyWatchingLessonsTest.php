<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OnlyWatchingLessonsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create one lesson, watch it and expect it
     * to generate first lesson watched
     *
     * @return void
     */
    public function test_watching_one_lesson()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 1);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements.0', 'First Lesson Watched')
            ->assertJsonPath('next_available_achievements.1', '5 Lessons Watched')
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 4 lessons, watch them and expect it
     * to not be sufficient for 5 lessons watched
     *
     * @return void
     */
    public function test_watching_four_lesson()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 4);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements.0', 'First Lesson Watched')
            ->assertJsonPath('next_available_achievements.1', '5 Lessons Watched')
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 25 lessons, watch them and expect it
     * to generate until 25 lessons watched
     *
     * @return void
     */
    public function test_watching_twentyfive_lessons()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 25);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched'
            ])
            ->assertJsonPath('next_available_achievements.1', '50 Lessons Watched')
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 4);
    }

    /**
     * Create 50 lessons, watch them and expect it
     * to generate until 50 lessons watched
     *
     * @return void
     */
    public function test_watching_fifty_lessons()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 50);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched'
            ])
            ->assertJsonMissingExact([
                'next_available_achievements' => [
                    '50 Lessons Watched'
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 99 lessons, watch them and expect it
     * to generate until 99 lessons watched
     *
     * @return void
     */
    public function test_watching_ninetynine_lessons()
    {
        $user = $this->createUser();
        $lessons = $this->createWatchedLesson($user, 99);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched'
            ])
            ->assertJsonMissingExact([
                'next_available_achievements' => [
                    '50 Lessons Watched'
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }
}
