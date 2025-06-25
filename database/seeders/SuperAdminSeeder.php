<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        if(User::where('email', env('SUPER_ADMIN_EMAIL'))->exists()) {
            return;
        }

        User::factory()->create([
            'email' => env('SUPER_ADMIN_EMAIL'),
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'role' => Role::SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);
    }
}
