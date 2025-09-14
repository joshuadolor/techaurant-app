<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', App\Http\Middleware\OwnerOnly::class])->group(function () {
    Route::resource('menus', 'App\Resources\Menu\Controllers\MenuController');
    Route::resource('menu-items', 'App\Resources\Menu\Controllers\MenuItemController');
    Route::resource('quick-menus', 'App\Resources\Menu\Controllers\JsonMenuController');
});

// remove after stabilization
if(app()->environment('local')) {
    Route::get('/seed/menus', function () {
        $seeder = new \App\Resources\Menu\Database\Seeders\ComprehensiveMenuSeeder();
        $seeder->run();
        
        return response()->json([
            'message' => 'Menus seeded successfully'
        ]);
    });
}
