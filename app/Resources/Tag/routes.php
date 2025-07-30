<?php

use Illuminate\Support\Facades\Route;

// @TODO: allow all users to create tags while also using the default tags created by super admin
Route::middleware([
    'auth:sanctum', 
    // App\Http\Middleware\OwnerOnly::class
])->group(function () {
    Route::resource('tags', 'App\Resources\Tag\Controllers\TagController');
});

// remove after stabilization
if(app()->environment('local')) {
    Route::get('/seed/tags', function () {
        $seeder = new \App\Resources\Tag\Database\Seeders\TagSeeder();
        $seeder->run();
        
        return response()->json([
            'message' => 'Tags seeded successfully'
        ]);
    });
} 