<?php

namespace App\Resources\Menu\Database\Factories;

use App\Models\User;
use App\Resources\Menu\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuCategoryFactory extends Factory
{
    protected $model = MenuCategory::class;

    public function definition()
    {
        $name = $this->faker->randomElement([
            'Appetizers',
            'Soups & Salads',
            'Main Courses',
            'Pasta & Risotto',
            'Seafood',
            'Grilled Meats',
            'Vegetarian Dishes',
            'Desserts',
            'Beverages',
            'Side Dishes',
            'Burgers & Sandwiches',
            'Pizza',
            'Sushi & Asian',
            'Mexican Favorites',
            'Italian Classics'
        ]);

        return [
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'description' => $this->faker->sentence(),
            'primary_image_url' => $this->faker->imageUrl(600, 400, 'food'),
            'is_active' => true,
            'is_available' => true,
            'owner_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the category is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the category has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn(array $attributes) => [
            'description' => null,
        ]);
    }

    /**
     * Indicate that the category has no image.
     */
    public function withoutImage(): static
    {
        return $this->state(fn(array $attributes) => [
            'primary_image_url' => null,
        ]);
    }
}
