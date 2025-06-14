<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Core\Controllers\ResourceController;
use App\Core\Services\ResourceService;
use App\Core\Repositories\ResourceRepository;
use App\Core\Transformers\ResourceTransformer;
use Illuminate\Support\Str;

class ResourceServiceProvider extends ServiceProvider
{
    private function registerResources($config)
    {
        $resourceName = $config['name'];
        $controllerName = $config['controller'] ?? null;
        $controller = ResourceController::class;

        if (!$controllerName) {
            $resourceSingular = Str::singular($resourceName);
            $controllerName = 'App\\Resources\\' . Str::studly($resourceSingular) . '\\Controllers\\' . Str::studly($resourceSingular) . 'Controller';
        }
        if (class_exists($controllerName)) {
            $controller = $controllerName;
        }

        $this->app->bind($controllerName, function ($app) use ($config, $controller) {
            $validation = $config['validation'];
            $service = $config['service'] ?? ResourceService::class;
            $repository = $config['repository'] ?? ResourceRepository::class;

            $resourceName = $config['name'];


            $searchableFields = $config['searchable'] ?? [];
            $sortableFields = $config['sortable'] ?? [];
            $filterableFields = $config['filterable'] ?? [];

            $fields = [
                'searchable' => $searchableFields,
                'sortable' => $sortableFields,
                'filterable' => $filterableFields,
            ];

            $transformer = $config['transformer'] ?? ResourceTransformer::class;

            $resourceRepository = new $repository(new $config['model'](), $searchableFields);
            $resourceService = new $service($resourceRepository, new $transformer(), $fields);
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

                continue;
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
