<?php

namespace App\Resources\Menu\Database\Factories;

use App\Resources\Menu\Models\MenuComboItem;
use App\Resources\Menu\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuComboItemFactory extends Factory
{
    protected $model = MenuComboItem::class;

    public function definition()
    {
        return [
            'main_menu_item_id' => MenuItem::factory(),
            'menu_item_id' => MenuItem::factory(),
            'quantity' => $this->faker->randomFloat(2, 0.5, 3.0),
        ];
    }

    /**
     * Indicate that the combo item has a quantity of 1.
     */
    public function singleQuantity(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity' => 1.00,
        ]);
    }

    /**
     * Indicate that the combo item has a quantity of 2.
     */
    public function doubleQuantity(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity' => 2.00,
        ]);
    }

    /**
     * Indicate that the combo item has a half quantity.
     */
    public function halfQuantity(): static
    {
        return $this->state(fn(array $attributes) => [
            'quantity' => 0.50,
        ]);
    }
}
