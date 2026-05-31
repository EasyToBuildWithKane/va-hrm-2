<?php

declare(strict_types=1);

namespace Modules\Employee\Resources;

use App\Http\Resources\BaseResource;

/** @mixin \Modules\Employee\Models\Employee */
class EmployeeResource extends BaseResource
{
    public function toArray($request): array
    {
        $canViewSalary = $request->user()?->hasPermissionTo('employee.salary.view');

        return [
            'id' => $this->ulid,
            'internal_id' => $this->id,
            'employee_number' => $this->employee_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim("{$this->first_name} {$this->last_name}"),
            'email' => $this->email,
            'phone' => $this->phone,
            'department' => $this->whenLoaded('department', fn () => [
                'id' => $this->department?->ulid,
                'name' => $this->department?->name,
            ]),
            'position' => $this->whenLoaded('position', fn () => [
                'id' => $this->position?->ulid,
                'name' => $this->position?->name,
            ]),
            'manager' => $this->whenLoaded('manager', fn () => $this->manager ? [
                'id' => $this->manager->ulid,
                'full_name' => "{$this->manager->first_name} {$this->manager->last_name}",
            ] : null),
            'employment_type' => $this->employment_type,
            'employment_status' => $this->employment_status,
            'join_date' => $this->join_date,
            'probation_end_date' => $this->probation_end_date,
            'termination_date' => $this->termination_date,
            'onboarding_status' => $this->onboarding_status,
            'offboarding_status' => $this->offboarding_status,
            'salary' => $canViewSalary ? $this->getRawOriginal('salary') : null,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
