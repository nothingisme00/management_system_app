<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'SuperAdmin'],
            ['name' => 'Admin'],
            ['name' => 'HRD'],
            ['name' => 'Manager'],
            ['name' => 'Karyawan'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
