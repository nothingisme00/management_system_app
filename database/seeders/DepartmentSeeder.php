<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Manages all IT systems and infrastructure'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Handles employee relations and HR processes'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Manages financial operations and accounting'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Oversees day-to-day operations'],
            ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Handles marketing and communications'],
        ];

        foreach ($departments as $dept) {
            \App\Models\Department::create([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'description' => $dept['description'],
                'is_active' => true,
            ]);
        }
    }
}
