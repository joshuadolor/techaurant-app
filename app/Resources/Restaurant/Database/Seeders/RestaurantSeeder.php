<?php

namespace App\Resources\Restaurant\Database\Seeders;

use App\Models\User;
use App\Resources\Restaurant\Models\Restaurant;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RestaurantSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run(): void
    {
        // Get or create a test user to be the owner
        for ($i = 0; $i < 10; $i++) {
            $owner = User::factory()->create(
                [
                    'name' => $this->faker->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );

            $ownerId = $owner->id;

            Restaurant::factory()
                ->count(4)
                ->create(['owner_id' => $ownerId]);
        }
    }
}
