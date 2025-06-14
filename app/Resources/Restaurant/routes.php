<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', App\Http\Middleware\OwnerOnly::class])->group(function () {
    Route::resource('restaurants', 'App\Resources\Restaurant\Controllers\RestaurantController');
});
