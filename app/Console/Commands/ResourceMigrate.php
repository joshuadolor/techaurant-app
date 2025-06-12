<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResourceMigrate extends Command
{
    protected $signature = 'resource:migrate 
        {--force : Force the operation to run when in production}
        {--rollback : Rollback the last batch of migrations}
        {--step=1 : The number of migrations to be rolled back}';

    protected $description = 'Run the database migrations for all resources';

    public function handle(): int
    {
        if ($this->option('rollback')) {
            $this->info('Rolling back resource migrations...');
            
            $result = $this->call('migrate:rollback', [
                '--path' => 'app/Resources/*/database/migrations',
                '--step' => $this->option('step'),
            ]);

            $this->info('Resource migrations rollback completed!');
        } else {
            $this->info('Running resource migrations...');

            $result = $this->call('migrate', [
                '--path' => 'app/Resources/*/database/migrations',
            ]);

            $this->info('Resource migrations completed!');
        }

        return $result;
    }
}
