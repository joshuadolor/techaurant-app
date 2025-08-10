<?php

namespace App\Resources\Menu\Database\Factories;

use App\Models\User;
use App\Resources\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        $name = $this->faker->randomElement([
            'Lunch Menu',
            'Dinner Menu',
            'Breakfast Menu',
            'Weekend Brunch',
            'Happy Hour Menu',
            'Kids Menu',
            'Vegetarian Menu',
            'Seasonal Menu',
            'Holiday Specials',
            'Chef\'s Tasting Menu'
        ]);

        return [
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'primary_image_url' => $this->faker->imageUrl(800, 600, 'food'),
            'is_active' => true,
            'is_available' => true,
            'owner_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the menu is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the menu is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the menu has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn(array $attributes) => [
            'description' => null,
        ]);
    }

    /**
     * Indicate that the menu has no image.
     */
    public function withoutImage(): static
    {
        return $this->state(fn(array $attributes) => [
            'primary_image_url' => null,
        ]);
    }
}
