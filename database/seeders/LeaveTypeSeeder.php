<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Leave\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Annual Leave', 'code' => 'ANNUAL', 'days_per_year' => 12, 'is_paid' => true, 'carry_forward' => true, 'max_carry_days' => 5],
            ['name' => 'Sick Leave', 'code' => 'SICK', 'days_per_year' => 10, 'is_paid' => true, 'requires_docs' => true],
            ['name' => 'Personal Leave', 'code' => 'PERSONAL', 'days_per_year' => 3, 'is_paid' => false, 'min_notice_days' => 3],
            ['name' => 'Maternity Leave', 'code' => 'MATERNITY', 'days_per_year' => 90, 'is_paid' => true],
            ['name' => 'Paternity Leave', 'code' => 'PATERNITY', 'days_per_year' => 7, 'is_paid' => true],
            ['name' => 'Bereavement Leave', 'code' => 'BEREAVEMENT', 'days_per_year' => 5, 'is_paid' => true],
        ];

        foreach ($types as $row) {
            LeaveType::firstOrCreate(['code' => $row['code']], $row);
        }
    }
}
