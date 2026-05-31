<?php

declare(strict_types=1);

namespace Modules\Employee\Services;

use Illuminate\Support\Str;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\EmployeeContract;

class EmployeeContractService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Employee $employee, array $data): EmployeeContract
    {
        $data['employee_id'] = $employee->id;
        $data['contract_number'] ??= 'CT-'.strtoupper(Str::random(10));

        return EmployeeContract::create($data);
    }
}
