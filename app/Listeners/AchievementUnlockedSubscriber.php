<?php

namespace App\Listeners;

use App\Events\{
    CommentWritten,
    LessonWatched,
    AchievementUnlocked,
    BadgeUnlocked
};
use App\Services\EarningService;

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
        $earnings = new EarningService(
            writtenCommentCount: $event->comment->user->comments->count(),
            watchedLessonCount: $event->comment->user->watched->count(),
        );

        # exact match means a new achievement should be unlocked
        if ($achievement = $earnings->ranks->get('comment')->get(
            $earnings->stats->pick('comment.count')
        )) {
            # calculate comment written earnings
            $earnings->stats->get('comment')->put('earnings', $earnings->getCommentAchievements(
                count: $earnings->stats->pick('comment.count')
            ));

            # calculate lesson watched earnings
            $earnings->stats->get('lesson')->put('earnings', $earnings->getLessonAchievements(
                count: $earnings->stats->pick('lesson.count')
            ));

            # emit achievement unlocked event
            AchievementUnlocked::dispatch(
                $achievement,
                $event->comment->user
            );

            # check to see if a new badge is unlocked
            if ($badge = $earnings->badges->get(
                $earnings->getCumulative()
            )) {
                BadgeUnlocked::dispatch(
                    $badge['title'],
                    $event->comment->user
                );
            }
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
        $earnings = new EarningService(
            writtenCommentCount: $event->user->comments->count(),
            watchedLessonCount: $event->user->watched->count(),
        );

        # exact match means a new achievement should be unlocked
        if ($achievement = $earnings->ranks->get('lesson')->get(
            $earnings->stats->pick('lesson.count')
        )) {
            # calculate comment written earnings
            $earnings->stats->get('comment')->put('earnings', $earnings->getCommentAchievements(
                count: $earnings->stats->pick('comment.count')
            ));

            # calculate lesson watched earnings
            $earnings->stats->get('lesson')->put('earnings', $earnings->getLessonAchievements(
                count: $earnings->stats->pick('lesson.count')
            ));

            # emit achievement unlocked event
            AchievementUnlocked::dispatch(
                $achievement,
                $event->user
            );

            # check to see if a new badge is unlocked
            if ($badge = $earnings->badges->get(
                $earnings->getCumulative()
            )) {
                BadgeUnlocked::dispatch(
                    $badge['title'],
                    $event->user
                );
            }
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
