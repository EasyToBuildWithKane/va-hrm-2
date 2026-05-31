<?php

declare(strict_types=1);

namespace Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeContractService;

class EmployeeContractController extends Controller
{
    public function __construct(private readonly EmployeeContractService $service)
    {
    }

    public function index(Employee $employee): JsonResponse
    {
        return ApiResponse::success($employee->contracts()->latest()->get());
    }

    public function store(Request $request, Employee $employee): JsonResponse
    {
        $data = $request->validate([
            'contract_type' => ['required', 'in:probation,fixed_term,permanent,freelance'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'base_salary' => ['nullable', 'numeric', 'min:0'],
            'file_path' => ['nullable', 'string'],
        ]);

        $contract = $this->service->create($employee, $data);

        return ApiResponse::success($contract, 'Contract created', status: 201);
    }
}
