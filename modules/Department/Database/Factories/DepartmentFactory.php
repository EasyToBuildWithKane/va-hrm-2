<?php

declare(strict_types=1);

namespace Modules\Department\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Department\Models\Department;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'ulid' => (string) Str::ulid(),
            'name' => $name,
            'code' => strtoupper(Str::slug(Str::limit($name, 8, ''), '')),
            'is_active' => true,
        ];
    }
}
