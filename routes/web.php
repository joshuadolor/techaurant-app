<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('verify-email/{token}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Catch all route for SPA
Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
