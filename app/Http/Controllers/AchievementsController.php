<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EarningService;

class AchievementsController extends Controller
{
    /**
     * Return a profile for
     * achievements and badges status
     *
     * @return json
     */
    public function index(User $user)
    {
        $earnings = new EarningService(
            writtenCommentCount: $user->comments->count(),
            watchedLessonCount: $user->watched->count(),
        );

        # calculate comment written earnings
        $earnings->stats->get('comment')->put('earnings', $earnings->getCommentAchievements(
            count: $earnings->stats->pick('comment.count')
        ));

        # calculate lesson watched earnings
        $earnings->stats->get('lesson')->put('earnings', $earnings->getLessonAchievements(
            count: $earnings->stats->pick('lesson.count')
        ));

        # get last earned badge
        $currentlyOwnedBadge = $earnings->getCurrentlyOwnedBadge(
            total: $earnings->getCumulative()
        );

        # get next badge to earn
        $nextEarnedBadge = $earnings->getNextBadgeToEarn(
            total: $earnings->getCumulative()
        );

        return response()->json([
            'unlocked_achievements' => collect()->merge(
                $earnings->getAchievements('comment')
            )->merge(
                $earnings->getAchievements('lesson')
            ),
            'next_available_achievements' => collect()->merge(
                $earnings->getNextAchievement('comment')
            )->merge(
                $earnings->getNextAchievement('lesson')
            ),
            'current_badge' => $earnings->getBadgeName($currentlyOwnedBadge),
            'next_badge' => $nextEarnedBadge ? $earnings->getBadgeName($nextEarnedBadge) : null,
            'remaing_to_unlock_next_badge' => $nextEarnedBadge ? $nextEarnedBadge - $earnings->getCumulative() : 0,
        ]);
    }
}
