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
        $user = User::where('role', 'user')->first() ?? User::factory()->create();

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
        $itemData = [
            [
                'name' => 'Bruschetta',
                'description' => 'Toasted bread topped with fresh tomatoes, basil, and olive oil',
                'price' => 8.99,
                'preparation_time' => 10,
                'slug' => 'bruschetta',
            ],
            [
                'name' => 'Caesar Salad',
                'description' => 'Crisp romaine lettuce with Caesar dressing, croutons, and parmesan',
                'price' => 12.99,
                'preparation_time' => 8,
                'slug' => 'caesar-salad',
            ],
            [
                'name' => 'Grilled Salmon',
                'description' => 'Fresh Atlantic salmon grilled to perfection with seasonal vegetables',
                'price' => 28.99,
                'preparation_time' => 20,
                'slug' => 'grilled-salmon',
            ],
            [
                'name' => 'Beef Burger',
                'description' => 'Juicy beef patty with lettuce, tomato, and special sauce',
                'price' => 16.99,
                'preparation_time' => 15,
                'slug' => 'beef-burger',
            ],
            [
                'name' => 'Garlic Bread',
                'description' => 'Warm bread brushed with garlic butter and herbs',
                'price' => 4.99,
                'preparation_time' => 5,
                'slug' => 'garlic-bread',
            ],
            [
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate layer cake with chocolate ganache',
                'price' => 9.99,
                'preparation_time' => 2,
                'slug' => 'chocolate-cake',
            ],
            [
                'name' => 'Iced Tea',
                'description' => 'Refreshing iced tea with lemon',
                'price' => 3.99,
                'preparation_time' => 1,
                'slug' => 'iced-tea',
            ],
            [
                'name' => 'Pasta Carbonara',
                'description' => 'Spaghetti with creamy carbonara sauce, bacon, and parmesan',
                'price' => 22.99,
                'preparation_time' => 18,
                'slug' => 'pasta-carbonara',
            ],
            [
                'name' => 'French Fries',
                'description' => 'Crispy golden fries seasoned with sea salt',
                'price' => 6.99,
                'preparation_time' => 8,
                'slug' => 'french-fries',
            ],
            [
                'name' => 'Tiramisu',
                'description' => 'Classic Italian dessert with coffee-soaked ladyfingers and mascarpone',
                'price' => 11.99,
                'preparation_time' => 2,
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
            $mainItem = $this->findMenuItemByName($menuItems, $comboDef['main_item_name']);
            
            if (!$mainItem) {
                $this->command->warn("Main item '{$comboDef['main_item_name']}' not found, skipping combo...");
                continue;
            }

            foreach ($comboDef['combo_items'] as $comboItemDef) {
                $comboItem = $this->findMenuItemByName($menuItems, $comboItemDef['name']);
                
                if (!$comboItem) {
                    $this->command->warn("Combo item '{$comboItemDef['name']}' not found, skipping...");
                    continue;
                }

                // Check if this combination already exists
                $existingCombo = \App\Resources\Menu\Models\MenuComboItem::where('main_menu_item_id', $mainItem->id)
                    ->where('menu_item_id', $comboItem->id)
                    ->first();

                if ($existingCombo) {
                    $this->command->info("Combo {$mainItem->name} + {$comboItem->name} already exists, skipping...");
                    $skippedCombos++;
                    continue;
                }

                // Create the combo relationship
                \App\Resources\Menu\Models\MenuComboItem::create([
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
     * Helper method to find menu item by name
     */
    private function findMenuItemByName(array $menuItems, string $name): ?\App\Resources\Menu\Models\MenuItem
    {
        foreach ($menuItems as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }
        return null;
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
