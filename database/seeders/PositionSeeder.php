<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = \App\Models\Department::all();

        $positions = [
            ['name' => 'Chief Executive Officer', 'code' => 'CEO', 'level' => 5, 'dept' => null],
            ['name' => 'Chief Technology Officer', 'code' => 'CTO', 'level' => 5, 'dept' => 'IT'],
            ['name' => 'Chief Financial Officer', 'code' => 'CFO', 'level' => 5, 'dept' => 'FIN'],
            ['name' => 'HR Manager', 'code' => 'HRM', 'level' => 4, 'dept' => 'HR'],
            ['name' => 'IT Manager', 'code' => 'ITM', 'level' => 4, 'dept' => 'IT'],
            ['name' => 'Finance Manager', 'code' => 'FNM', 'level' => 4, 'dept' => 'FIN'],
            ['name' => 'Operations Manager', 'code' => 'OPM', 'level' => 4, 'dept' => 'OPS'],
            ['name' => 'Senior Developer', 'code' => 'SDE', 'level' => 3, 'dept' => 'IT'],
            ['name' => 'Senior Accountant', 'code' => 'SAC', 'level' => 3, 'dept' => 'FIN'],
            ['name' => 'HR Specialist', 'code' => 'HRS', 'level' => 2, 'dept' => 'HR'],
            ['name' => 'Junior Developer', 'code' => 'JDE', 'level' => 2, 'dept' => 'IT'],
            ['name' => 'Staff', 'code' => 'STF', 'level' => 1, 'dept' => null],
        ];

        foreach ($positions as $pos) {
            $deptId = null;
            if ($pos['dept']) {
                $dept = $departments->firstWhere('code', $pos['dept']);
                $deptId = $dept?->id;
            }

            \App\Models\Position::create([
                'name' => $pos['name'],
                'code' => $pos['code'],
                'description' => 'Position for '.$pos['name'],
                'level' => $pos['level'],
                'department_id' => $deptId,
                'is_active' => true,
            ]);
        }
    }
}
