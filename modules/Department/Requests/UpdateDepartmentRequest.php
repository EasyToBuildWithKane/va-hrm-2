<?php

declare(strict_types=1);

namespace Modules\Department\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($departmentId)],
            'parent_id' => ['nullable', 'exists:departments,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'headcount_limit' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'metadata' => ['array'],
        ];
    }
}
