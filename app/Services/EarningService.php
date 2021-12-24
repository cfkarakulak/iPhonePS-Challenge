<?php

namespace App\Services;

use Illuminate\Support\Collection;

class EarningService
{
    /**
     * The list of ranks consisting
     * of comments and watched lessons.
     *
     * @var array
     */
    public $ranks;

    /**
     * Collection of counts and earnings
     *
     * @var array
     */
    public $stats;

    /**
     * Collection of badges
     * for convenience
     *
     * @var array
     */
    public $badges;

    /**
     * Create collection of
     * achievements and badges
     *
     * @return void
     */
    public function __construct(
        int $writtenCommentCount,
        int $watchedLessonCount
    ) {
        $this->ranks = collect([
            'comment' => collect(
                config('ranking.achievements.comments')
            ),
            'lesson' => collect(
                config('ranking.achievements.lessons')
            ),
        ]);

        $this->stats = collect([
            'comment' => collect([
                'count' => $writtenCommentCount,
                'earnings' => null,
            ]),
            'lesson' => collect([
                'count' => $watchedLessonCount,
                'earnings' => null,
            ]),
        ]);

        $this->badges = collect(
            config('ranking.badges')
        );
    }

    /**
     * Find out what achievements user has earned
     * by given comment count
     *
     * @param  int $count
     * @return Illuminate\Support\Collection
     */
    public function getCommentAchievements(int $count) : Collection
    {
        return $this->ranks->get('comment')->keysUntil($count);
    }

    /**
     * Find out what achievements user has earned
     * by given lesson count
     *
     * @param  int $count
     * @return Illuminate\Support\Collection
     */
    public function getLessonAchievements(int $count) : Collection
    {
        return $this->ranks->get('lesson')->keysUntil($count);
    }

    /**
     * Get the names of achievements by intersecting
     * the keys of achievements array
     *
     * @param  string type: comment,lesson
     * @return Illuminate\Support\Collection
     */
    public function getAchievements(string $type) : Collection
    {
        return $this->ranks->get($type)->intersectByKeys(
            $this->stats->get($type)->get('earnings')->flip()
        );
    }

    /**
     * Get the sum of the number of achievements for both
     * comments and lessons watched
     *
     * @return int
     */
    public function getCumulative() : int
    {
        return $this->stats->pluck('earnings')->map('count')->sum() ?: 0;
    }

    /**
     * Get the name of the next achievement
     *
     * @param  string type: comment,lesson
     * @return string if exists, otherwise null
     */
    public function getNextAchievement(string $type) : ?string
    {
        return $this->ranks->get($type)->diffKeys(
            $this->stats->get($type)->get('earnings')->flip()
        )->sortKeys()->first();
    }

    /**
     * Get the array key of the currently owned badge
     *
     * @param  int $total
     * @return int
     */
    public function getCurrentlyOwnedBadge(int $total) : int
    {
        return $this->badges->nearest($total, 'smaller') ?: 0;
    }

    /**
     * Get the array key of the next badge that can be earned
     *
     * @param  int $total
     * @return int
     */
    public function getNextBadgeToEarn(int $total) : ?int
    {
        return $this->badges->nearest($total, 'bigger');
    }

    /**
     * Get the name of the given badge
     *
     * @param  string type: comment,lesson
     * @return string
     */
    public function getBadgeName(int $stage) : string
    {
        return data_get($this->badges->get($stage), 'key', 'N/A');
    }
}
