<?php

namespace App\Resources\Tag\Database\Seeders;

use App\Models\User;
use App\Resources\Tag\Models\Tag;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TagSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run(): void
    {
        // Get or create a test user to be the owner
        $owners = User::query()->inRandomOrder()->limit(20)->get();
        // Create tags by super admin
        $superAdmin = User::where('email', env('SUPER_ADMIN_EMAIL', 'admin@admin.com'))->first();
        if ($superAdmin) {
            $commonTags = [
                'Best Seller',
                'New',
                'Must try',
                'Featured',
                'Trending',
                'Popular',
                'Chicken',
                'Beef',
                'Pork',
                'Fish',
                'Vegetarian',
                'Vegan',
                'Gluten Free',
                'Spicy',
                'Halal',
                'Kosher',
                'Dairy Free',
                'Nut Free',
                'Has Nuts',
                'Has Dairy',
                'Seafood',
                'Organic'
            ];

            foreach ($commonTags as $tagName) {
                Tag::factory()->create([
                    'name' => $tagName,
                    'owner_id' => $superAdmin->id
                ]);
            }
        }
        // for ($i = 0; $i < 20; $i++) {
        //     $owner = $owners->random();

        //     $ownerId = $owner->id;

        //     Tag::factory()
        //         ->count(5)
        //         ->forOwner($ownerId)
        //         ->create();
        // }
    }
} 