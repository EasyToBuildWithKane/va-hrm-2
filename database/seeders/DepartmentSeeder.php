<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Department\Models\Department;
use Modules\Department\Models\Position;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            ['name' => 'Executive', 'code' => 'EXEC', 'positions' => ['CEO', 'CTO', 'CFO']],
            ['name' => 'Human Resources', 'code' => 'HR', 'positions' => ['HR Director', 'HR Specialist', 'Recruiter']],
            ['name' => 'Engineering', 'code' => 'ENG', 'positions' => ['Engineering Manager', 'Senior Engineer', 'Engineer']],
            ['name' => 'Finance', 'code' => 'FIN', 'positions' => ['Finance Manager', 'Accountant']],
            ['name' => 'IT Support', 'code' => 'IT', 'positions' => ['IT Manager', 'IT Support Specialist']],
            ['name' => 'Operations', 'code' => 'OPS', 'positions' => ['Operations Manager', 'Operations Analyst']],
        ];

        foreach ($structure as $entry) {
            $department = Department::firstOrCreate(
                ['code' => $entry['code']],
                [
                    'ulid' => (string) Str::ulid(),
                    'name' => $entry['name'],
                    'is_active' => true,
                ],
            );

            foreach ($entry['positions'] as $positionName) {
                Position::firstOrCreate(
                    ['code' => strtoupper(Str::slug($positionName, '_'))],
                    [
                        'ulid' => (string) Str::ulid(),
                        'name' => $positionName,
                        'department_id' => $department->id,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
