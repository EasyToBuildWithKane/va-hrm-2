<?php

declare(strict_types=1);

namespace Modules\Provisioning\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\SoftwareLicense;
use Modules\Provisioning\Services\ProvisioningService;

class SoftwareLicenseController extends Controller
{
    public function __construct(private readonly ProvisioningService $service)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(SoftwareLicense::query()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'vendor' => ['nullable', 'string'],
            'license_key' => ['nullable', 'string'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        return ApiResponse::success(SoftwareLicense::create($data), 'License created', status: 201);
    }

    public function update(Request $request, SoftwareLicense $license): JsonResponse
    {
        $license->update($request->validate([
            'name' => ['sometimes', 'string'],
            'total_seats' => ['sometimes', 'integer'],
            'expires_at' => ['nullable', 'date'],
        ]));

        return ApiResponse::success($license, 'License updated');
    }

    public function assign(Request $request, SoftwareLicense $license): JsonResponse
    {
        $data = $request->validate(['employee_id' => ['required', 'exists:employees,id']]);

        $this->service->assignLicense(Employee::findOrFail($data['employee_id']), $license);

        return ApiResponse::message('License assigned');
    }

    public function revoke(SoftwareLicense $license, Employee $employee): JsonResponse
    {
        $this->service->revokeLicense($employee, $license);

        return ApiResponse::message('License revoked');
    }
}
