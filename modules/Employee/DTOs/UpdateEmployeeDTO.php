<?php

declare(strict_types=1);

namespace Modules\Employee\DTOs;

use Illuminate\Http\Request;

final class UpdateEmployeeDTO
{
    /**
     * @param  array<string, mixed>  $changes
     */
    public function __construct(public readonly array $changes)
    {
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->only([
            'first_name', 'last_name', 'email', 'phone',
            'department_id', 'position_id', 'manager_id',
            'employment_type', 'employment_status',
            'join_date', 'probation_end_date',
            'salary', 'bank_account_number', 'metadata',
        ]));
    }
}
