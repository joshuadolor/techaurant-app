<?php

namespace App\Resources\Menu\Database\Seeders;

use App\Resources\Menu\Models\JsonMenu;
use App\Resources\Restaurant\Models\Restaurant;
use Illuminate\Database\Seeder;

class JsonMenuSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::query()->first();
        if (!$restaurant) {
            return;
        }

        JsonMenu::updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            ['data' => [
                'name' => 'Main Menu',
                'categories' => [
                    [
                        'id' => 'cat1',
                        'name' => 'Appetizers',
                        'items' => [
                            ['id' => 'item1', 'name' => 'Caesar Salad', 'price' => 6.5, 'available' => true],
                            ['id' => 'item2', 'name' => 'Bruschetta', 'price' => 5.0, 'available' => true],
                        ],
                    ],
                ],
            ]]
        );
    }
}


