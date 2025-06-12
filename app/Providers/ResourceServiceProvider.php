<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ResourceServiceProvider extends ServiceProvider
{
    private function registerResources($config)
    {
        $controller = $config['controller'];

        $this->app->bind($controller, function ($app) use ($config, $controller) {
            $validation = $config['validation'];
            $service = $config['service'];
            $repository = $config['repository'];

            $resourceName = $config['name'];
            $resourceRepository = new $repository(new $config['model'](), $config['searchable']);
            $resourceService = new $service($resourceRepository);

            return new $controller($resourceService, $validation, $resourceName);
        });
    }

    public function register(): void
    {
        $resourcesPath = app_path('Resources');
        if (!File::exists($resourcesPath)) {
            return;
        }

        foreach (File::directories($resourcesPath) as $resourcePath) {
            $configPath = "{$resourcePath}/config.php";
            if (file_exists($configPath)) {
                $config = require $configPath;
            } else {
                continue;
            }

            if (array_is_list($config) && count($config) > 0) {
                foreach ($config as $key => $value) {
                    $this->registerResources($value);
                }

                return;
            }

            $this->registerResources($config);
        }
    }

    public function boot(): void
    {
        $resourcesPath = app_path('Resources');

        if (!File::exists($resourcesPath)) {
            return;
        }

        // Load routes from each module
        foreach (File::directories($resourcesPath) as $resourcePath) {
            $routesPath = "{$resourcePath}/routes.php";

            if (File::exists($routesPath)) {
                Route::middleware('api')
                    ->prefix('api/resource')
                    ->group(function () use ($routesPath) {
                        require $routesPath;
                    });
            }
        }
    }
}
