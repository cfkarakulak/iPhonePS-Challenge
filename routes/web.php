<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\CommentWrittenImposterController;
use App\Http\Controllers\LessonWatchedImposterController;
use App\Http\Controllers\UserImposterController;

Route::post('/users/create', [UserImposterController::class, 'store']);

Route::prefix('/users/{user}')->group(function () {
    Route::get('achievements', [AchievementsController::class, 'index']);

    # routes to mimic comment written/lesson watched behaviour
    Route::post('comment', [CommentWrittenImposterController::class, 'store']);
    Route::post('watch', [LessonWatchedImposterController::class, 'store']);
});
