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

Route::prefix("v1")->group(function () {
    Route::apiResource('achievements', App\Http\Controllers\Api\V1\AchievementController::class);
    Route::apiResource('challengers', ChallengerController::class)->only('show');


    Route::get('challengers/{challenger}/challenges', [ChallengerController::class, 'challenges'])->name('challengers.challenges');
    Route::post('challengers', [ChallengerController::class, 'store'])->name('challengers.store');


    Route::apiResource('challenges', ChallengeController::class)->only('index', 'show', 'store');
    Route::apiResource('ranks', App\Http\Controllers\Api\V1\RankController::class);


    Route::prefix("runner/")->middleware('login.access')->group(function () {
        Route::post('/on/{engine}/{challenge}', [App\Http\Controllers\CodeRunnerController::class, 'getChallengeEditor']);
        Route::post('/check/{engine}/{challenge}', [App\Http\Controllers\CodeRunnerController::class, 'run']);
        Route::post('/submit', [App\Http\Controllers\CodeRunnerController::class, 'submit']);
    });
    Route::get('profile/', [App\Http\Controllers\Api\V1\ChallengerController::class, 'getProfile'])
        ->middleware('login.access');


});
