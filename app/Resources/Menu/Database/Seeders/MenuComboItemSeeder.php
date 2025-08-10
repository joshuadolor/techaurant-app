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
        // Define realistic combo combinations - each combination is unique
        $comboDefinitions = [
            // Burger Combo: Burger + Fries + Drink
            [
                'main_item_name' => 'Beef Burger',
                'combo_items' => [
                    ['name' => 'French Fries', 'quantity' => 1.00],
                    ['name' => 'Iced Tea', 'quantity' => 1.00],
                ]
            ],
            // Salmon Combo: Salmon + Side + Drink
            [
                'main_item_name' => 'Grilled Salmon',
                'combo_items' => [
                    ['name' => 'Garlic Bread', 'quantity' => 1.00],
                    ['name' => 'Iced Tea', 'quantity' => 1.00],
                ]
            ],
            // Pasta Combo: Pasta + Side + Salad
            [
                'main_item_name' => 'Pasta Carbonara',
                'combo_items' => [
                    ['name' => 'Garlic Bread', 'quantity' => 1.00],
                    ['name' => 'Caesar Salad', 'quantity' => 1.00],
                ]
            ],
            // Appetizer Combo: Multiple appetizers
            [
                'main_item_name' => 'Bruschetta',
                'combo_items' => [
                    ['name' => 'Caesar Salad', 'quantity' => 1.00],
                    ['name' => 'Garlic Bread', 'quantity' => 1.00],
                ]
            ],
            // Dessert Combo: Multiple desserts
            [
                'main_item_name' => 'Chocolate Cake',
                'combo_items' => [
                    ['name' => 'Tiramisu', 'quantity' => 1.00],
                    ['name' => 'Iced Tea', 'quantity' => 1.00],
                ]
            ],
            // Caesar Salad Combo: Salad + Side + Drink
            [
                'main_item_name' => 'Caesar Salad',
                'combo_items' => [
                    ['name' => 'Garlic Bread', 'quantity' => 0.50], // Half portion
                    ['name' => 'Iced Tea', 'quantity' => 1.00],
                ]
            ],
        ];

        $createdCombos = 0;
        $skippedCombos = 0;

        foreach ($comboDefinitions as $comboDef) {
            $mainItem = $menuItems->where('name', $comboDef['main_item_name'])->first();
            
            if (!$mainItem) {
                $this->command->warn("Main item '{$comboDef['main_item_name']}' not found, skipping combo...");
                continue;
            }

            foreach ($comboDef['combo_items'] as $comboItemDef) {
                $comboItem = $menuItems->where('name', $comboItemDef['name'])->first();
                
                if (!$comboItem) {
                    $this->command->warn("Combo item '{$comboItemDef['name']}' not found, skipping...");
                    continue;
                }

                // Check if this combination already exists
                $existingCombo = MenuComboItem::where('main_menu_item_id', $mainItem->id)
                    ->where('menu_item_id', $comboItem->id)
                    ->first();

                if ($existingCombo) {
                    $this->command->info("Combo {$mainItem->name} + {$comboItem->name} already exists, skipping...");
                    $skippedCombos++;
                    continue;
                }

                // Create the combo relationship
                MenuComboItem::create([
                    'main_menu_item_id' => $mainItem->id,
                    'menu_item_id' => $comboItem->id,
                    'quantity' => $comboItemDef['quantity'],
                ]);

                $createdCombos++;
                $this->command->info("Created combo: {$mainItem->name} + {$comboItem->name} (qty: {$comboItemDef['quantity']})");
            }
        }

        $this->command->info("Created {$createdCombos} new combo items, skipped {$skippedCombos} existing combinations");
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
