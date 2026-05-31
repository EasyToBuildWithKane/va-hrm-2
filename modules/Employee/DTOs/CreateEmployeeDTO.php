<?php

declare(strict_types=1);

namespace Modules\Employee\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

final class CreateEmployeeDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly int $departmentId,
        public readonly int $positionId,
        public readonly ?int $managerId,
        public readonly string $employmentType,
        public readonly Carbon $joinDate,
        public readonly ?Carbon $probationEndDate,
        public readonly ?string $employeeNumber,
        public readonly ?float $salary,
        public readonly ?int $userId,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            firstName: $request->string('first_name')->toString(),
            lastName: $request->string('last_name')->toString(),
            email: $request->string('email')->toString(),
            phone: $request->string('phone')->toString() ?: null,
            departmentId: (int) $request->integer('department_id'),
            positionId: (int) $request->integer('position_id'),
            managerId: $request->filled('manager_id') ? (int) $request->integer('manager_id') : null,
            employmentType: $request->string('employment_type')->toString(),
            joinDate: Carbon::parse($request->input('join_date')),
            probationEndDate: $request->filled('probation_end_date')
                ? Carbon::parse($request->input('probation_end_date'))
                : null,
            employeeNumber: $request->string('employee_number')->toString() ?: null,
            salary: $request->filled('salary') ? (float) $request->input('salary') : null,
            userId: $request->filled('user_id') ? (int) $request->integer('user_id') : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'department_id' => $this->departmentId,
            'position_id' => $this->positionId,
            'manager_id' => $this->managerId,
            'employment_type' => $this->employmentType,
            'join_date' => $this->joinDate->toDateString(),
            'probation_end_date' => $this->probationEndDate?->toDateString(),
            'employee_number' => $this->employeeNumber,
            'salary' => $this->salary,
            'user_id' => $this->userId,
        ];
    }
}
