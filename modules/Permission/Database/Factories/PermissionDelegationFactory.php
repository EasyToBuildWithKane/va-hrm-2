<?php

declare(strict_types=1);

namespace Modules\Permission\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Permission\Models\PermissionDelegation;

class PermissionDelegationFactory extends Factory
{
    protected $model = PermissionDelegation::class;

    public function definition(): array
    {
        return [
            'delegated_by' => User::factory(),
            'delegated_to' => User::factory(),
            'delegation_type' => 'permission',
            'permission' => 'approval.approve',
            'reason' => $this->faker->sentence(),
            'valid_from' => now(),
            'valid_until' => now()->addDays(7),
            'created_by' => User::factory(),
        ];
    }
}
