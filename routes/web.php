<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\CommentWrittenImposterController;
use App\Http\Controllers\LessonWatchedImposterController;

Route::prefix('/users/{user}')->group(function () {
    Route::get('achievements', [AchievementsController::class, 'index']);

    # routes to mimic comment written/lesson watched behaviour
    Route::get('comment', [CommentWrittenImposterController::class, 'store']);
    Route::get('watch', [LessonWatchedImposterController::class, 'store']);
});
