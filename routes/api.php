<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('account')->group(function () {
    // Public routes
    Route::post('forgot-password', [AccountController::class, 'forgotPassword']);
    Route::post('reset-password', [AccountController::class, 'resetPassword']);
    Route::post('register', [AccountController::class, 'createAccount']); 

     // Email verification routes
    Route::post('email/verification-notification', [AccountController::class, 'resendVerificationEmail'])
        ->middleware('throttle:6,1', 'auth:sanctum')
        ->name('verification.send');
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh'])
        ->name('refresh');

    // Social authentication routes
    Route::get('social/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('social/{provider}/callback', [SocialAuthController::class, 'callback']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('resources/users', UserController::class);
});
