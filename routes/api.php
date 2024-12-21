<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;   

use App\Http\Controllers\UserController;
use App\Http\Controllers\ScoreController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::post('/users/login', [UserController::class, 'login']); // New login route
Route::post('/scores', [ScoreController::class, 'store']);
Route::get('/users/{user_id}/highest-score', [ScoreController::class, 'highestScoreForUser']);
Route::post('/users/{user_id}/complete-game', [ScoreController::class, 'completeGame']);
Route::get('/topscores', [ScoreController::class, 'topThreeScores']);
Route::get('/users/{user_id}/games-played', [ScoreController::class, 'gamesPlayed']); // New route for games played


Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
});