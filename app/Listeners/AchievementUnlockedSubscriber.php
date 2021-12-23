<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;

class AchievementUnlockedSubscriber
{
    /**
     * Handle the comment written event.
     *
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function commentWritten(CommentWritten $event)
    {
        # exact match means a new achievement should be unlocked
        if ($achievement = collect(config('ranking.achievements.comments'))->get(
            $event->comment->user->comments->count()
        )) {
            return event(
                new AchievementUnlocked(
                    $achievement,
                    $event->comment->user,
                )
            );
        }
    }

    /**
     * Handle the lesson watched event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function lessonWatched(LessonWatched $event)
    {
        # exact match means a new achievement should be unlocked
        if ($achievement = collect(config('ranking.achievements.lessons'))->get(
            $event->user->lessons->count()
        )) {
            return event(
                new AchievementUnlocked(
                    $achievement,
                    $event->user,
                )
            );
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            CommentWritten::class,
            AchievementUnlockedSubscriber::class . '@commentWritten'
        );

        $events->listen(
            LessonWatched::class,
            AchievementUnlockedSubscriber::class . '@lessonWatched'
        );
    }
}
