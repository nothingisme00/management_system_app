<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SuperAdmin User
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();

        if ($superAdminRole) {
            User::firstOrCreate(
                ['email' => 'superadmin@example.com'],
                [
                    'name' => 'Super Administrator',
                    'password' => Hash::make('password'),
                    'role_id' => $superAdminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        }

        // Create Admin User
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
