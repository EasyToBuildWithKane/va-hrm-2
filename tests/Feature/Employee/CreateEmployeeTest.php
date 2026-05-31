<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Department\Models\Department;
use Modules\Department\Models\Position;
use Modules\Employee\Events\EmployeeCreated;
use Modules\Employee\Models\Employee;
use Modules\Permission\Models\Role;
use Tests\TestCase;

class CreateEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_hr_staff_can_create_employee(): void
    {
        \Event::fake([EmployeeCreated::class]);

        $department = Department::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'HR',
            'code' => 'HR-T',
            'is_active' => true,
        ]);
        $position = Position::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Specialist',
            'code' => 'SPEC-T',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $hrUser = User::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'HR Staff',
            'email' => 'hr@example.com',
            'password' => bcrypt('secret'),
            'status' => 'active',
        ]);
        $hrUser->assignRole(Role::query()->where('name', 'HR Staff')->firstOrFail());

        $this->actingAs($hrUser, 'sanctum')
            ->postJson('/api/v1/employees', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@company.com',
                'department_id' => $department->id,
                'position_id' => $position->id,
                'employment_type' => 'full_time',
                'join_date' => now()->toDateString(),
                'user_id' => $hrUser->id,
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.email', 'john.doe@company.com');

        $this->assertDatabaseHas('employees', ['email' => 'john.doe@company.com']);
        \Event::assertDispatched(EmployeeCreated::class);
    }

    public function test_employee_role_cannot_create_other_employee(): void
    {
        $user = User::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Employee',
            'email' => 'emp@example.com',
            'password' => bcrypt('secret'),
            'status' => 'active',
        ]);
        $user->assignRole('Employee');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/employees', [])
            ->assertStatus(403);
    }
}
