<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;

// Social auth routes
Route::get('auth/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'user']);
}); 