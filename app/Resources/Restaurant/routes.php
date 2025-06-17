<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', App\Http\Middleware\OwnerOnly::class])->group(function () {
    Route::resource('restaurants', 'App\Resources\Restaurant\Controllers\RestaurantController');
});

// remove after stabilization
if(app()->environment('local')) {
    Route::get('/seed/restaurants', function () {
        $seeder = new \App\Resources\Restaurant\Database\Seeders\RestaurantSeeder();
        $seeder->run();
        
        return response()->json([
            'message' => 'Restaurants seeded successfully'
        ]);
    });
}
