<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;

// Social auth routes
Route::get('auth/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Catch all route for SPA
Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
