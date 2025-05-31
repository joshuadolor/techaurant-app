<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (config('resources') as $resource => $config) {
            // Register Model
            $this->app->bind($config['model'], function ($app) use ($config) {
                return new $config['model']();
            });

            // Register Service
            $this->app->bind($config['service'], function ($app) use ($config) {
                return new $config['service'](
                    $app->make($config['model'])
                );
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
