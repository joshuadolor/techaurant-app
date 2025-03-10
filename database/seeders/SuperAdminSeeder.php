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
        User::updateOrCreate([
            'email' => env('SUPER_ADMIN_EMAIL'),
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'role' => Role::SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);
    }
} 