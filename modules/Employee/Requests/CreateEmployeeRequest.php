<?php

declare(strict_types=1);

namespace Modules\Employee\Requests;

use App\Http\Requests\BaseFormRequest;

class CreateEmployeeRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,intern'],
            'employment_status' => ['sometimes', 'in:active,inactive,on_leave,terminated,resigned'],
            'join_date' => ['required', 'date'],
            'probation_end_date' => ['nullable', 'date', 'after:join_date'],
            'employee_number' => ['nullable', 'string', 'max:20', 'unique:employees,employee_number'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'exists:users,id'],
            'metadata' => ['array'],
        ];
    }
}
