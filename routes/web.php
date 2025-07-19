<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\RestaurantPublicController;

Route::get('verify-email/{token}', [AccountController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/rid/{uuid}', [RestaurantPublicController::class, 'showByUuid'])
    ->name('restaurant.uuid')
    ->middleware('throttle:60,1');

Route::get('/r/{restaurant:slug}', [RestaurantPublicController::class, 'show'])
    ->name('restaurant.show')
    ->middleware('throttle:60,1');

// Catch all route for SPA
Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
