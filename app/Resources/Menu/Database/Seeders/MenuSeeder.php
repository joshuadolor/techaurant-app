<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Models\User;
use App\Resources\Menu\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if seeder has already been run
        if ($this->hasAlreadyRun()) {
            $this->command->info('Menu seeder has already been run. Skipping...');
            return;
        }

        $this->command->info('Seeding Menus...');

        // Get or create a user for ownership
        $user = User::where('role', 'user')->first() ?? User::factory()->create();

        // Create 2 menus using factory
        Menu::factory(2)->create([
            'owner_id' => $user->id,
        ]);

        $this->command->info('Created 2 menus');

        // Mark seeder as run
        $this->markAsRun();

        $this->command->info('Menu seeding completed successfully!');
    }

    /**
     * Check if seeder has already been run
     */
    private function hasAlreadyRun(): bool
    {
        // Check if we have any menus
        return Menu::count() > 0;
    }

    /**
     * Mark seeder as run
     */
    private function markAsRun(): void
    {
        $this->command->info('Menu seeder marked as completed');
    }
}
