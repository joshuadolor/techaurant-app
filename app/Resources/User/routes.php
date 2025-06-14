<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', App\Http\Middleware\AdminOnly::class])->group(function () {
    Route::resource('users', 'App\Resources\User\Controllers\UserController');
});
