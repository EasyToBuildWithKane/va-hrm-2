<?php

declare(strict_types=1);

namespace Modules\Department\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Department\Models\Department;
use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;
use Modules\Department\Requests\StoreDepartmentRequest;
use Modules\Department\Requests\UpdateDepartmentRequest;
use Modules\Department\Resources\DepartmentResource;
use Modules\Department\Services\DepartmentService;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly DepartmentService $service,
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Department::class);

        $paginated = $this->repository->paginate(
            $request->only(['search', 'is_active', 'parent_id', 'sort', 'direction']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success(
            DepartmentResource::collection($paginated)->resolve(),
            meta: [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        );
    }

    public function show(Department $department): JsonResponse
    {
        $this->authorize('view', $department);

        return ApiResponse::success(DepartmentResource::make($department->load(['manager', 'parent']))->resolve(request()));
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $this->authorize('create', Department::class);

        $department = $this->service->create($request->validated());

        return ApiResponse::success(
            DepartmentResource::make($department)->resolve(request()),
            'Department created',
            status: 201,
        );
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $this->authorize('update', $department);

        $department = $this->service->update($department, $request->validated());

        return ApiResponse::success(
            DepartmentResource::make($department)->resolve(request()),
            'Department updated',
        );
    }

    public function destroy(Department $department): JsonResponse
    {
        $this->authorize('delete', $department);

        $department->delete();

        return ApiResponse::message('Department deleted');
    }

    public function employees(Department $department): JsonResponse
    {
        return ApiResponse::success($department->employees()->get());
    }

    public function hierarchy(Department $department): JsonResponse
    {
        return ApiResponse::success($this->repository->hierarchy($department->id));
    }

    public function analytics(Department $department): JsonResponse
    {
        return ApiResponse::success($this->service->analytics($department));
    }

    public function headcount(Department $department): JsonResponse
    {
        return ApiResponse::success(['department_id' => $department->id, 'headcount' => $this->repository->headcount($department->id)]);
    }
}
