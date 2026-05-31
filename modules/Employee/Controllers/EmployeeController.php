<?php

declare(strict_types=1);

namespace Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Actions\CreateEmployeeAction;
use Modules\Employee\Actions\OffboardEmployeeAction;
use Modules\Employee\Actions\OnboardEmployeeAction;
use Modules\Employee\Actions\TerminateEmployeeAction;
use Modules\Employee\Actions\UpdateEmployeeAction;
use Modules\Employee\DTOs\CreateEmployeeDTO;
use Modules\Employee\DTOs\UpdateEmployeeDTO;
use Modules\Employee\Models\Employee;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use Modules\Employee\Requests\CreateEmployeeRequest;
use Modules\Employee\Requests\UpdateEmployeeRequest;
use Modules\Employee\Resources\EmployeeListResource;
use Modules\Employee\Resources\EmployeeResource;
use Modules\Employee\Services\EmployeeService;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $repository,
        private readonly EmployeeService $service,
        private readonly CreateEmployeeAction $createAction,
        private readonly UpdateEmployeeAction $updateAction,
        private readonly TerminateEmployeeAction $terminateAction,
        private readonly OnboardEmployeeAction $onboardAction,
        private readonly OffboardEmployeeAction $offboardAction,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Employee::class);

        $paginated = $this->repository->paginate(
            $request->only(['search', 'status', 'department_id', 'employment_type', 'from', 'to', 'sort', 'direction']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success(
            EmployeeListResource::collection($paginated)->resolve($request),
            meta: [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        );
    }

    public function show(Employee $employee): JsonResponse
    {
        $this->authorize('view', $employee);

        return ApiResponse::success(
            EmployeeResource::make($employee->load(['department', 'position', 'manager']))->resolve(request())
        );
    }

    public function store(CreateEmployeeRequest $request): JsonResponse
    {
        $this->authorize('create', Employee::class);

        $employee = ($this->createAction)(CreateEmployeeDTO::fromRequest($request));

        return ApiResponse::success(
            EmployeeResource::make($employee)->resolve($request),
            'Employee created successfully',
            status: 201,
        );
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $this->authorize('update', $employee);

        $employee = ($this->updateAction)($employee, UpdateEmployeeDTO::fromRequest($request));

        return ApiResponse::success(
            EmployeeResource::make($employee)->resolve($request),
            'Employee updated',
        );
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return ApiResponse::message('Employee archived');
    }

    public function restore(string $ulid): JsonResponse
    {
        $employee = Employee::withTrashed()->where('ulid', $ulid)->firstOrFail();
        $this->authorize('restore', $employee);

        $employee->restore();

        return ApiResponse::success(EmployeeResource::make($employee)->resolve(request()), 'Employee restored');
    }

    public function timeline(Employee $employee): JsonResponse
    {
        $this->authorize('view', $employee);

        return ApiResponse::success($employee->timeline()->limit(100)->get());
    }

    public function onboard(Employee $employee): JsonResponse
    {
        $this->authorize('onboard', $employee);

        ($this->onboardAction)($employee);

        return ApiResponse::message('Employee onboarded');
    }

    public function offboard(Employee $employee): JsonResponse
    {
        $this->authorize('terminate', $employee);

        ($this->offboardAction)($employee);

        return ApiResponse::message('Employee offboarded');
    }

    public function terminate(Request $request, Employee $employee): JsonResponse
    {
        $this->authorize('terminate', $employee);

        $data = $request->validate([
            'reason' => ['required', 'string'],
            'effective_date' => ['nullable', 'date'],
        ]);

        ($this->terminateAction)($employee, $data['reason'], $data['effective_date'] ?? null);

        return ApiResponse::message('Employee termination initiated');
    }

    public function transfer(Request $request, Employee $employee): JsonResponse
    {
        $this->authorize('update', $employee);

        $data = $request->validate(['department_id' => ['required', 'exists:departments,id']]);

        $employee = $this->service->transferDepartment($employee, $data['department_id']);

        return ApiResponse::success(EmployeeResource::make($employee)->resolve($request), 'Employee transferred');
    }
}
