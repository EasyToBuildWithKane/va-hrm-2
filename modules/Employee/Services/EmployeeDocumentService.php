<?php

declare(strict_types=1);

namespace Modules\Employee\Services;

use Modules\Employee\Models\Employee;
use Modules\Employee\Models\EmployeeDocument;

class EmployeeDocumentService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Employee $employee, array $data): EmployeeDocument
    {
        $data['employee_id'] = $employee->id;
        $data['uploaded_by'] = auth()->id();

        return EmployeeDocument::create($data);
    }
}
