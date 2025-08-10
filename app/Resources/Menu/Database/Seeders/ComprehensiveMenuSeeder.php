<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Models\User;
use App\Resources\Menu\Models\Menu;
use App\Resources\Menu\Models\MenuCategory;
use App\Resources\Menu\Models\MenuItem;
use Illuminate\Database\Seeder;

class ComprehensiveMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if seeder has already been run
        if ($this->hasAlreadyRun()) {
            $this->command->info('Comprehensive Menu seeder has already been run. Skipping...');
            return;
        }

        $this->command->info('Seeding comprehensive Menu data...');

        // Get or create a user for ownership
        $users = User::where('role', 'user')->inRandomOrder()->limit(10)->get();
        if ($users->count() < 10) {
            $usersToCreate = 10 - $users->count();
            $users = $users->merge(User::factory()->count($usersToCreate)->create());
        }

        foreach ($users as $user) {
            // Create 2 menus
            $menus = $this->createMenus($user);
            $this->command->info('Created ' . count($menus) . ' menus');
    
            // Create 5 categories
            $categories = $this->createCategories($user);
            $this->command->info('Created ' . count($categories) . ' categories');
    
            // Create 10 menu items
            $menuItems = $this->createMenuItems($user);
            $this->command->info('Created ' . count($menuItems) . ' menu items');
    
            // Associate categories with menus
            $this->associateCategoriesWithMenus($menus, $categories);
            $this->command->info('Associated categories with menus');
    
            // Associate menu items with categories
            $this->associateMenuItemsWithCategories($menuItems, $categories);
            $this->command->info('Associated menu items with categories');
    
            // Create combo items
            $this->createComboItems($menuItems);
            $this->command->info('Created combo items');
        }

        // Mark seeder as run
        $this->markAsRun();

        $this->command->info('Comprehensive Menu seeding completed successfully!');
    }

    /**
     * Create menus
     */
    private function createMenus(User $user): array
    {
        $menuData = [
            [
                'name' => 'Lunch Menu',
                'description' => 'Our delicious lunch offerings available from 11 AM to 3 PM',
                'slug' => 'lunch-menu',
            ],
            [
                'name' => 'Dinner Menu',
                'description' => 'Our exquisite dinner selection available from 5 PM to 10 PM',
                'slug' => 'dinner-menu',
            ],
        ];

        $menus = [];
        foreach ($menuData as $data) {
            $menus[] = Menu::create(array_merge($data, [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'owner_id' => $user->id,
                'is_active' => true,
                'is_available' => true,
            ]));
        }

        return $menus;
    }

    /**
     * Create categories
     */
    private function createCategories(User $user): array
    {
        $categoryData = [
            ['name' => 'Appetizers', 'description' => 'Start your meal with our delicious appetizers'],
            ['name' => 'Main Courses', 'description' => 'Our signature main dishes'],
            ['name' => 'Sides', 'description' => 'Perfect accompaniments to your main course'],
            ['name' => 'Desserts', 'description' => 'Sweet endings to your dining experience'],
            ['name' => 'Beverages', 'description' => 'Refreshing drinks and cocktails'],
        ];

        $categories = [];
        foreach ($categoryData as $data) {
            $categories[] = MenuCategory::create(array_merge($data, [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'owner_id' => $user->id,
                'is_active' => true,
                'is_available' => true,
            ]));
        }

        return $categories;
    }

    /**
     * Create menu items
     */
    private function createMenuItems(User $user): array
    {
        $faker = \Faker\Factory::create();

        $itemData = [
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true), 
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => $faker->unique()->words(2, true),
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => $faker->unique()->slug(2),
            ],
            [
                'name' => 'Tiramisu', // Keep this one fixed as it's used as a marker
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 40),
                'preparation_time' => $faker->numberBetween(1, 30),
                'slug' => 'tiramisu',
            ],
        ];

        $menuItems = [];
        foreach ($itemData as $data) {
            $menuItems[] = MenuItem::create(array_merge($data, [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'owner_id' => $user->id,
                'is_active' => true,
                'is_available' => true,
            ]));
        }

        return $menuItems;
    }

    /**
     * Associate categories with menus
     */
    private function associateCategoriesWithMenus(array $menus, array $categories): void
    {
        // Lunch menu gets appetizers, main courses, sides, and beverages
        $menus[0]->categories()->attach([
            $categories[0]->id, // Appetizers
            $categories[1]->id, // Main Courses
            $categories[2]->id, // Sides
            $categories[4]->id, // Beverages
        ]);

        // Dinner menu gets all categories
        $menus[1]->categories()->attach(array_column($categories, 'id'));
    }

    /**
     * Associate menu items with categories
     */
    private function associateMenuItemsWithCategories(array $menuItems, array $categories): void
    {
        // Appetizers
        $categories[0]->menuItems()->attach([
            $menuItems[0]->id, // Bruschetta
            $menuItems[1]->id, // Caesar Salad
        ]);

        // Main Courses
        $categories[1]->menuItems()->attach([
            $menuItems[2]->id, // Grilled Salmon
            $menuItems[3]->id, // Beef Burger
            $menuItems[7]->id, // Pasta Carbonara
        ]);

        // Sides
        $categories[2]->menuItems()->attach([
            $menuItems[4]->id, // Garlic Bread
            $menuItems[8]->id, // French Fries
        ]);

        // Desserts
        $categories[3]->menuItems()->attach([
            $menuItems[5]->id, // Chocolate Cake
            $menuItems[9]->id, // Tiramisu
        ]);

        // Beverages
        $categories[4]->menuItems()->attach([
            $menuItems[6]->id, // Iced Tea
        ]);
    }

    /**
     * Create combo items
     */
    private function createComboItems(array $menuItems): void
    {
        if (count($menuItems) < 3) {
            $this->command->warn('Need at least 3 menu items to create combos. Skipping...');
            return;
        }

        $createdCombos = 0;
        $skippedCombos = 0;

        // Create combos by randomly selecting items and ensuring uniqueness
        $mainItems = array_slice($menuItems, 0, min(5, count($menuItems))); // Select up to 5 main items
        
        foreach ($mainItems as $mainItem) {
            // For each main item, create 1-3 combo items
            $comboCount = rand(1, 3);
            $availableComboItems = array_filter($menuItems, fn($item) => $item->id !== $mainItem->id);
            $selectedComboItems = array_slice($availableComboItems, 0, min($comboCount, count($availableComboItems)));
            
            foreach ($selectedComboItems as $comboItem) {
                // Check if this combination already exists
                $existingCombo = \App\Resources\Menu\Models\MenuComboItem::where('main_menu_item_id', $mainItem->id)
                    ->where('menu_item_id', $comboItem->id)
                    ->first();

                if ($existingCombo) {
                    $this->command->info("Combo {$mainItem->name} + {$comboItem->name} already exists, skipping...");
                    $skippedCombos++;
                    continue;
                }

                // Create the combo relationship with random quantity
                $quantity = $this->getRandomQuantity();
                
                \App\Resources\Menu\Models\MenuComboItem::create([
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
        // Check if we have the specific menu item that indicates seeding is complete
        return MenuItem::where('slug', 'tiramisu')->exists();
    }

    /**
     * Mark seeder as run
     */
    private function markAsRun(): void
    {
        // The seeder is considered run when all items are created
        // We use the existence of the last item (Tiramisu) as our marker
        $this->command->info('Comprehensive seeder marked as completed');
    }
}
