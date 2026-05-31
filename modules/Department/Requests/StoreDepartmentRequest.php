<?php

declare(strict_types=1);

namespace Modules\Department\Requests;

use App\Http\Requests\BaseFormRequest;

class StoreDepartmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
            'parent_id' => ['nullable', 'exists:departments,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'headcount_limit' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'metadata' => ['array'],
        ];
    }
}
