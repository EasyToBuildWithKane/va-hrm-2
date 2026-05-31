<?php

declare(strict_types=1);

namespace Modules\Employee\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'first_name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'last_name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'email' => ['sometimes', 'email', Rule::unique('employees', 'email')->ignore($employeeId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['sometimes', 'exists:departments,id'],
            'position_id' => ['sometimes', 'exists:positions,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'employment_type' => ['sometimes', 'in:full_time,part_time,contract,intern'],
            'employment_status' => ['sometimes', 'in:active,inactive,on_leave,terminated,resigned'],
            'salary' => ['sometimes', 'numeric', 'min:0'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'metadata' => ['array'],
        ];
    }
}
