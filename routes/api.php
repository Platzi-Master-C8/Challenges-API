<?php

use App\Http\Controllers\Api\V1\ChallengeController;
use App\Http\Controllers\Api\V1\ChallengerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("v1")->group(function () {
    Route::apiResource('achievements', App\Http\Controllers\Api\V1\AchievementController::class);
    Route::apiResource('challengers', ChallengerController::class)->only('show');
    Route::get('challengers/{challenger}/challenges', [ChallengerController::class, 'challenges'])->name('challengers.challenges');
    Route::apiResource('challenges', ChallengeController::class)->only('index', 'show');
    Route::apiResource('ranks', App\Http\Controllers\Api\V1\RankController::class);
});
//Routes which

// Create routes to give the possibility to start a container and run code from it.
Route::prefix("runner/")->group(function () {
    Route::get('/on/{container}', function () {
    });


    Route::get('/check_answer/{container}', function () {
    });
});
