<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\CommentWrittenImposterController;
use App\Http\Controllers\LessonWatchedImposterController;

Route::prefix('/users/{user}')->group(function () {
    Route::get('achievements', [AchievementsController::class, 'index']);

    # routes to mimic comment written/lesson watched behaviour
    # amount should strictly be a positive number, see RouteServiceProvider:52
    Route::get('comment/{amount}', [CommentWrittenImposterController::class, 'store']);
    Route::get('watch/{amount}', [LessonWatchedImposterController::class, 'store']);
});
