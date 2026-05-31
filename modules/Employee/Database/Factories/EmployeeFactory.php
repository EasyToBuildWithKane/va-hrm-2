<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Department\Models\Department;
use Modules\Department\Models\Position;
use Modules\Employee\Models\Employee;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'ulid' => (string) Str::ulid(),
            'user_id' => User::factory(),
            'employee_number' => 'EMP-'.strtoupper(Str::random(8)),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'employment_type' => 'full_time',
            'employment_status' => 'active',
            'join_date' => now()->subMonths(rand(1, 36))->toDateString(),
            'onboarding_status' => 'completed',
            'offboarding_status' => 'none',
        ];
    }
}
