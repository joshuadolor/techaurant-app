<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ResourceSeed extends Command
{
    protected $signature = 'resource:seed 
        {resource? : The name of the resource to seed}
        {--count=1 : Number of records to create}
        {--multiply=1 : Multiply the count by this number}
        {--force : Force the operation to run when in production}';

    protected $description = 'Seed the database with records from resource factories';

    public function handle(): int
    {
        $resource = $this->argument('resource');
        $count = $this->option('count') * $this->option('multiply');
        $resourcesPath = app_path('Resources');

        if (!File::exists($resourcesPath)) {
            $this->error('Resources directory not found!');
            return 1;
        }

        if ($resource) {
            // Seed specific resource
            return $this->seedResource($resource, $count);
        }

        // Seed all resources
        foreach (File::directories($resourcesPath) as $resourcePath) {
            $resourceName = basename($resourcePath);
            $this->seedResource($resourceName, $count);
        }

        return 0;
    }

    protected function seedResource(string $resource, int $count): int
    {
        $resource = Str::studly($resource);
        $factoryPath = app_path("Resources/{$resource}/database/factories");
        
        if (!File::exists($factoryPath)) {
            $this->warn("No factories found for resource: {$resource}");
            return 1;
        }

        foreach (File::files($factoryPath) as $file) {
            $factoryClass = "App\\Resources\\{$resource}\\Database\\Factories\\" . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            
            if (!class_exists($factoryClass)) {
                $this->warn("Factory class not found: {$factoryClass}");
                continue;
            }

            $model = (new $factoryClass)->modelName();
            $this->info("Seeding {$count} records for {$model}...");

            try {
                $factoryClass::factory()->count($count)->create();
                $this->info("✓ Successfully seeded {$count} records for {$model}");
            } catch (\Exception $e) {
                $this->error("Failed to seed {$model}: " . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}