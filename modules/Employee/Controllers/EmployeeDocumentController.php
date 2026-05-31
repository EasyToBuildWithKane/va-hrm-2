<?php

declare(strict_types=1);

namespace Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeDocumentService;

class EmployeeDocumentController extends Controller
{
    public function __construct(private readonly EmployeeDocumentService $service)
    {
    }

    public function index(Employee $employee): JsonResponse
    {
        return ApiResponse::success($employee->documents()->latest()->get());
    }

    public function store(Request $request, Employee $employee): JsonResponse
    {
        $data = $request->validate([
            'document_type' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string'],
            'mime_type' => ['nullable', 'string', 'max:80'],
            'size_bytes' => ['nullable', 'integer', 'min:0'],
            'issued_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $document = $this->service->create($employee, $data);

        return ApiResponse::success($document, 'Document uploaded', status: 201);
    }
}
