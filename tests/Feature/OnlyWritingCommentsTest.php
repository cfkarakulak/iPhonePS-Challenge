<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;

class OnlyWritingCommentsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create one comment and expect it
     * to generate first comment written
     *
     * @return void
     */
    public function test_writing_one_comment()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 1);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements.0', 'First Comment Written')
            ->assertJsonPath('next_available_achievements.0', '3 Comments Written')
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 5 comments and expect it
     * to generate until 5 comments written
     *
     * @return void
     */
    public function test_writing_five_comments()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 5);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written'
            ])
            ->assertJsonPath('next_available_achievements.0', '10 Comments Written')
            ->assertJsonPath('current_badge', 'beginner')
            ->assertJsonPath('next_badge', 'intermediate')
            ->assertJsonPath('remaining_to_unlock_next_badge', 1);
    }

    /**
     * Create 19 comments, watch them and expect it
     * to not be sufficient for 20 comments written
     *
     * @return void
     */
    public function test_writing_nineteen_comments()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 19);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
            ])
            ->assertJsonPath('next_available_achievements.0', '20 Comments Written')
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 4);
    }

    /**
     * Create 20 comments and expect it
     * to generate until 20 comments written
     *
     * @return void
     */
    public function test_writing_twenty_comments()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 20);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written'
            ])
            ->assertJsonMissingExact([
                'next_available_achievements' => [
                    '20 Comments Written'
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }

    /**
     * Create 99 comments and expect it
     * to generate until 20 comments written
     *
     * @return void
     */
    public function test_writing_ninetynine_comments()
    {
        $user = $this->createUser();
        $comments = $this->createWrittenComment($user, 99);

        $response = $this->get("/users/{$user->id}/achievements");

        $response
            ->assertJsonPath('unlocked_achievements', [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written'
            ])
            ->assertJsonMissingExact([
                'next_available_achievements' => [
                    '20 Comments Written'
                ]
            ])
            ->assertJsonPath('current_badge', 'intermediate')
            ->assertJsonPath('next_badge', 'advanced')
            ->assertJsonPath('remaining_to_unlock_next_badge', 3);
    }
}
