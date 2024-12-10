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
Route::post('/scores', [ScoreController::class, 'store']);
Route::get('/users/{user_id}/highest-score', [ScoreController::class, 'highestScoreForUser']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
});