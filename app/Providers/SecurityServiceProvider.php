<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class SecurityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Set secure cookie options
        Config::set('session.secure', true);
        Config::set('session.same_site', 'lax');
        Config::set('session.http_only', true);

        // Add security headers middleware
        Route::pushMiddlewareToGroup('web', \App\Http\Middleware\SecurityHeaders::class);
        Route::pushMiddlewareToGroup('api', \App\Http\Middleware\SecurityHeaders::class);

        // Set rate limiting    
        Route::middleware('throttle:api')->group(function () {
            Route::prefix('api')->group(base_path('routes/api.php'));
        });
    }
}