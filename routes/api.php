<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PersonController;
use App\Http\Controllers\API\LikesController;

// Person Routes
Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class, 'index']); // Get all people
    Route::get('/recommended', [PersonController::class, 'getRecommended']); // Get recommended people
    Route::get('/{id}', [PersonController::class, 'show']); // Get person by ID
    Route::post('/', [PersonController::class, 'store']); // Create new person
});

// Likes Routes
Route::prefix('likes')->group(function () {
    Route::post('/like', [LikesController::class, 'like']); // Like a person
    Route::post('/dislike', [LikesController::class, 'dislike']); // Dislike a person
    Route::get('/liked-people', [LikesController::class, 'getLikedPeople']); // Get all liked people
    Route::get('/disliked-people', [LikesController::class, 'getDislikedPeople']); // Get all disliked people
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
