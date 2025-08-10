<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Models\User;
use App\Resources\Menu\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if seeder has already been run
        if ($this->hasAlreadyRun()) {
            $this->command->info('MenuCategory seeder has already been run. Skipping...');
            return;
        }

        $this->command->info('Seeding Menu Categories...');

        // Get or create a user for ownership
        $user = User::where('role', 'user')->first() ?? User::factory()->create();

        // Create 5 categories using factory
        MenuCategory::factory(5)->create([
            'owner_id' => $user->id,
        ]);

        $this->command->info('Created 5 menu categories');

        // Mark seeder as run
        $this->markAsRun();

        $this->command->info('MenuCategory seeding completed successfully!');
    }

    /**
     * Check if seeder has already been run
     */
    private function hasAlreadyRun(): bool
    {
        // Check if we have any menu categories
        return MenuCategory::count() > 0;
    }

    /**
     * Mark seeder as run
     */
    private function markAsRun(): void
    {
        $this->command->info('MenuCategory seeder marked as completed');
    }
}
