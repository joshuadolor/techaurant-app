<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateResource extends Command
{
    protected $signature = 'make:resource {name : The name of the resource}';
    protected $description = 'Create a new resource with all necessary files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $singular = Str::singular($name);
        $plural = Str::plural($name);
        $module = Str::studly($singular);

        // Create Model
        $this->call('make:model', [
            'name' => $module
        ]);

        // Create Migration
        $this->call('make:migration', [
            'name' => "create_{$plural}_table"
        ]);

        // Create Controller
        $this->call('make:controller', [
            'name' => "Api/{$module}Controller",
            '--api' => true
        ]);
    }
}
