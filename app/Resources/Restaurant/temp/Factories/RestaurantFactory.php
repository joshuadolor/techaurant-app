<?php

namespace App\Resources\Restaurant\Database\Factories;

use App\Resources\Restaurant\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition()
    {
        $name = $this->faker->company();

        return [
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'slug' => Str::slug($name),
            'tagline' => $this->faker->catchPhrase(),
            'description' => $this->faker->paragraph(),
            'subdomain' => Str::slug($name),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the restaurant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the restaurant has no subdomain.
     */
    public function withoutSubdomain(): static
    {
        return $this->state(fn(array $attributes) => [
            'subdomain' => null,
        ]);
    }
}
