<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('restaurants', 'App\Resources\Restaurant\Controllers\RestaurantController');  // Use the name from config
});
