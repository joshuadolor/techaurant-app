<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Models\User;
use App\Resources\Menu\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if seeder has already been run
        if ($this->hasAlreadyRun()) {
            $this->command->info('MenuItem seeder has already been run. Skipping...');
            return;
        }

        $this->command->info('Seeding Menu Items...');

        // Get or create a user for ownership
        $user = User::where('role', 'user')->first() ?? User::factory()->create();

        // Create 10 menu items using factory
        MenuItem::factory(10)->create([
            'owner_id' => $user->id,
        ]);

        $this->command->info('Created 10 menu items');

        // Mark seeder as run
        $this->markAsRun();

        $this->command->info('MenuItem seeding completed successfully!');
    }

    /**
     * Check if seeder has already been run
     */
    private function hasAlreadyRun(): bool
    {
        // Check if we have any menu items
        return MenuItem::count() > 0;
    }

    /**
     * Mark seeder as run
     */
    private function markAsRun(): void
    {
        $this->command->info('MenuItem seeder marked as completed');
    }
}
