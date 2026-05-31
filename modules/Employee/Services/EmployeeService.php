<?php

declare(strict_types=1);

namespace Modules\Employee\Services;

use App\Enums\EmploymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Employee\DTOs\CreateEmployeeDTO;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\EmployeeTimeline;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepositoryInterface $repository)
    {
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        return $this->repository->emailExists($email, $excludeId);
    }

    public function create(CreateEmployeeDTO $dto): Employee
    {
        return DB::transaction(function () use ($dto): Employee {
            $data = $dto->toArray();
            $data['employee_number'] = $data['employee_number'] ?: $this->generateEmployeeNumber();
            $data['user_id'] ??= $this->ensureUserExists($dto);

            $employee = $this->repository->create($data);
            $this->logTimeline($employee, 'created', 'Employee created');

            return $employee;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data): Employee {
            $previousDepartment = $employee->department_id;
            $employee = $this->repository->update($employee, $data);

            if (isset($data['department_id']) && $data['department_id'] !== $previousDepartment) {
                $this->logTimeline($employee, 'department_transfer', "Transferred to department #{$data['department_id']}");
            }

            return $employee;
        });
    }

    public function terminate(Employee $employee, string $reason, ?string $effectiveDate = null): Employee
    {
        return DB::transaction(function () use ($employee, $reason, $effectiveDate): Employee {
            $employee = $this->repository->update($employee, [
                'employment_status' => EmploymentStatus::TERMINATED->value,
                'termination_date' => $effectiveDate ?? now()->toDateString(),
                'offboarding_status' => 'in_progress',
            ]);

            $this->logTimeline($employee, 'terminated', 'Employee terminated', ['reason' => $reason]);

            return $employee;
        });
    }

    public function transferDepartment(Employee $employee, int $newDepartmentId): Employee
    {
        return $this->update($employee, ['department_id' => $newDepartmentId]);
    }

    public function completeOnboarding(Employee $employee): Employee
    {
        $employee = $this->repository->update($employee, ['onboarding_status' => 'completed']);
        $this->logTimeline($employee, 'onboarded', 'Onboarding completed');

        return $employee;
    }

    public function completeOffboarding(Employee $employee): Employee
    {
        $employee = $this->repository->update($employee, ['offboarding_status' => 'completed']);
        $this->logTimeline($employee, 'offboarded', 'Offboarding completed');

        return $employee;
    }

    private function generateEmployeeNumber(): string
    {
        do {
            $candidate = 'EMP-'.strtoupper(Str::random(8));
        } while (Employee::query()->where('employee_number', $candidate)->exists());

        return $candidate;
    }

    private function ensureUserExists(CreateEmployeeDTO $dto): int
    {
        return \App\Models\User::query()
            ->firstOrCreate(
                ['email' => $dto->email],
                [
                    'name' => trim("{$dto->firstName} {$dto->lastName}"),
                    'password' => bcrypt(Str::password(16)),
                    'status' => 'active',
                ],
            )->id;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function logTimeline(Employee $employee, string $eventType, string $title, array $payload = []): void
    {
        EmployeeTimeline::create([
            'employee_id' => $employee->id,
            'event_type' => $eventType,
            'title' => $title,
            'payload' => $payload,
            'occurred_at' => now(),
            'performed_by' => auth()->id(),
        ]);
    }
}
