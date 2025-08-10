<?php

namespace App\Resources\Menu\Database\Factories;

use App\Models\User;
use App\Resources\Menu\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        $name = $this->faker->randomElement([
            'Grilled Salmon',
            'Caesar Salad',
            'Margherita Pizza',
            'Beef Burger',
            'Chicken Pasta',
            'Vegetable Stir Fry',
            'Chocolate Cake',
            'Iced Tea',
            'Garlic Bread',
            'Fish Tacos',
            'Beef Steak',
            'Mushroom Risotto',
            'Greek Salad',
            'Tiramisu',
            'Lemonade'
        ]);

        return [
            'uuid' => (string) Str::uuid(),
            'slug' => Str::slug($name),
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 5.00, 45.00),
            'preparation_time' => $this->faker->randomElement([5, 10, 15, 20, 25, 30]),
            'primary_image_url' => $this->faker->imageUrl(600, 400, 'food'),
            'is_active' => true,
            'is_available' => true,
            'owner_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the menu item is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the menu item is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the menu item is free.
     */
    public function free(): static
    {
        return $this->state(fn(array $attributes) => [
            'price' => null,
        ]);
    }

    /**
     * Indicate that the menu item has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn(array $attributes) => [
            'description' => null,
        ]);
    }

    /**
     * Indicate that the menu item has no image.
     */
    public function withoutImage(): static
    {
        return $this->state(fn(array $attributes) => [
            'primary_image_url' => null,
        ]);
    }

    /**
     * Indicate that the menu item has no preparation time.
     */
    public function withoutPreparationTime(): static
    {
        return $this->state(fn(array $attributes) => [
            'preparation_time' => null,
        ]);
    }
}
