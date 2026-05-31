<?php

declare(strict_types=1);

namespace Modules\Department\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Department\Models\Department;
use Modules\Department\Models\Position;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $name = $this->faker->jobTitle();

        return [
            'ulid' => (string) Str::ulid(),
            'name' => $name,
            'code' => strtoupper(Str::slug($name.'-'.Str::random(4))),
            'department_id' => Department::factory(),
            'is_active' => true,
        ];
    }
}
