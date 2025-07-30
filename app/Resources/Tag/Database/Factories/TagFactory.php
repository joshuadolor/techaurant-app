<?php

namespace App\Resources\Tag\Database\Factories;

use App\Resources\Tag\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => $this->faker->unique()->word(),
        ];
    }

    /**
     * Indicate that the tag is for a specific owner.
     */
    public function forOwner(int $ownerId): static
    {
        return $this->state(fn(array $attributes) => [
            'owner_id' => $ownerId,
        ]);
    }
} 