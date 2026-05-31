<?php

declare(strict_types=1);

namespace Modules\Employee\Resources;

use App\Http\Resources\BaseResource;

/** @mixin \Modules\Employee\Models\Employee */
class EmployeeListResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ulid,
            'employee_number' => $this->employee_number,
            'full_name' => trim("{$this->first_name} {$this->last_name}"),
            'email' => $this->email,
            'department' => $this->department?->name,
            'position' => $this->position?->name,
            'employment_status' => $this->employment_status,
            'employment_type' => $this->employment_type,
            'join_date' => $this->join_date,
        ];
    }
}
