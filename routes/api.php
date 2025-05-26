<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SocialPostController;
use App\Http\Controllers\Api\PostGenerationController;

Route::get('/test-api', function () {
    return response()->json(['message' => 'API is working']);
});
// Public API routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/posts/generate', [PostGenerationController::class, 'generate'])->middleware('throttle:3,1');
    Route::apiResource('posts', SocialPostController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});