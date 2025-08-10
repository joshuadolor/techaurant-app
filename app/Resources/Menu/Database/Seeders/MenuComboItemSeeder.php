<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Models\User;
use App\Resources\Menu\Models\MenuComboItem;
use App\Resources\Menu\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuComboItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if seeder has already been run
        if ($this->hasAlreadyRun()) {
            $this->command->info('MenuComboItem seeder has already been run. Skipping...');
            return;
        }

        $this->command->info('Seeding Menu Combo Items...');

        // Get or create a user for ownership
        $user = User::where('role', 'user')->first() ?? User::factory()->create();

        // Ensure we have menu items to work with
        $menuItems = MenuItem::all();
        if ($menuItems->count() < 5) {
            $this->command->warn('Need at least 5 menu items to create combos. Creating them first...');
            MenuItem::factory(5)->create(['owner_id' => $user->id]);
            $menuItems = MenuItem::all();
        }

        // Create realistic combo combinations
        $this->createComboItems($menuItems);
        $this->command->info('Created ' . MenuComboItem::count() . ' unique combo items');

        // Mark seeder as run
        $this->markAsRun();

        $this->command->info('MenuComboItem seeding completed successfully!');
    }

    /**
     * Create realistic combo combinations
     */
    private function createComboItems($menuItems): void
    {
        if ($menuItems->count() < 3) {
            $this->command->warn('Need at least 3 menu items to create combos. Skipping...');
            return;
        }

        $createdCombos = 0;
        $skippedCombos = 0;

        // Create combos by randomly selecting items and ensuring uniqueness
        $mainItems = $menuItems->random(min(5, $menuItems->count())); // Select up to 5 main items
        
        foreach ($mainItems as $mainItem) {
            // For each main item, create 1-3 combo items
            $comboCount = rand(1, 3);
            $availableComboItems = $menuItems->where('id', '!=', $mainItem->id)->random(min($comboCount, $menuItems->count() - 1));
            
            foreach ($availableComboItems as $comboItem) {
                // Check if this combination already exists
                $existingCombo = MenuComboItem::where('main_menu_item_id', $mainItem->id)
                    ->where('menu_item_id', $comboItem->id)
                    ->first();

                if ($existingCombo) {
                    $this->command->info("Combo {$mainItem->name} + {$comboItem->name} already exists, skipping...");
                    $skippedCombos++;
                    continue;
                }

                // Create the combo relationship with random quantity
                $quantity = $this->getRandomQuantity();
                
                MenuComboItem::create([
                    'main_menu_item_id' => $mainItem->id,
                    'menu_item_id' => $comboItem->id,
                    'quantity' => $quantity,
                ]);

                $createdCombos++;
                $this->command->info("Created combo: {$mainItem->name} + {$comboItem->name} (qty: {$quantity})");
            }
        }

        $this->command->info("Created {$createdCombos} new combo items, skipped {$skippedCombos} existing combinations");
    }

    /**
     * Get a random quantity for combo items
     */
    private function getRandomQuantity(): float
    {
        $quantities = [0.5, 1.0, 1.5, 2.0];
        return $quantities[array_rand($quantities)];
    }

    /**
     * Check if seeder has already been run
     */
    private function hasAlreadyRun(): bool
    {
        // Check if we have any combo items
        return MenuComboItem::count() > 0;
    }

    /**
     * Mark seeder as run
     */
    private function markAsRun(): void
    {
        $this->command->info('MenuComboItem seeder marked as completed');
    }
}
