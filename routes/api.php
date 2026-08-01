<?php

use App\Http\Controllers\Api\Social\FriendshipController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::put('/user/settings', [UserController::class, 'updateUserSettings']);

    // User Discovery & Profile Routes
    Route::get('/users', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    // Friendship Endpoints
    Route::get('/friends', [FriendshipController::class, 'index']);
    Route::get('/friends/pending', [FriendshipController::class, 'pending']);
    Route::post('/friends/request', [FriendshipController::class, 'sendRequest']);
    Route::patch('/friends/{user}/accept', [FriendshipController::class, 'acceptRequest']);
    Route::delete('/friends/{user}/decline', [FriendshipController::class, 'declineRequest']);
    Route::delete('/friends/{user}', [FriendshipController::class, 'unfriend']);
    Route::post('/friends/{user}/block', [FriendshipController::class, 'blockUser']);
    Route::delete('/friends/{user}/unblock', [FriendshipController::class, 'unblockUser']);
});
