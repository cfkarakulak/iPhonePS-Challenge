<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ranking
    |--------------------------------------------------------------------------
    |
    | This file is meant to hold all achievements and badges
    | that can possibly be unlocked, array keys denote the stage that users are at
    | to expand the functionality, simply add 100 => '100 Lessons Watched'
    |
    */

    'achievements' => [
        'lessons' => [
            1 => 'First Lesson Watched',
            5 => '5 Lessons Watched',
            10 => '10 Lessons Watched',
            25 => '25 Lessons Watched',
            50 => '50 Lessons Watched',
        ],

        'comments' => [
            1 => 'First Comment Written',
            3 => '3 Comments Written',
            5 => '5 Comments Written',
            10 => '10 Comment Written',
            20 => '20 Comment Written',
        ],
    ],

    'badges' => [
        'beginner' => '0 Achievements',
        'intermediate' => '4 Achievements',
        'advanced' => '8 Achievements',
        'master' => '10 Achievements',
    ],
];
