<?php

declare(strict_types=1);

namespace Modules\Leave\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Models\Employee;
use Modules\Leave\Actions\SubmitLeaveRequestAction;
use Modules\Leave\Models\LeavePolicy as LeavePolicyModel;
use Modules\Leave\Models\LeaveQuota;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Models\LeaveType;
use Modules\Leave\Repositories\Contracts\LeaveRepositoryInterface;
use Modules\Leave\Services\LeaveService;

class LeaveController extends Controller
{
    public function __construct(
        private readonly LeaveRepositoryInterface $repository,
        private readonly LeaveService $service,
        private readonly SubmitLeaveRequestAction $submitAction,
    ) {
    }

    public function types(): JsonResponse
    {
        return ApiResponse::success(LeaveType::query()->where('is_active', true)->get());
    }

    public function myQuotas(Request $request): JsonResponse
    {
        $employee = $request->user()->employee;
        abort_unless($employee !== null, 422, 'No employee profile');

        return ApiResponse::success(
            $this->repository->quotasForEmployee($employee->id, (int) now()->year)
        );
    }

    public function quotasFor(Employee $employee): JsonResponse
    {
        return ApiResponse::success($this->repository->quotasForEmployee($employee->id, (int) now()->year));
    }

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->repository->paginate(
            $request->only(['employee_id', 'status', 'leave_type_id', 'from', 'to']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
        ]);

        $employee = $request->user()->employee;
        abort_unless($employee !== null, 422, 'No employee profile');

        $leaveRequest = ($this->submitAction)($employee, $data, $request->user());

        return ApiResponse::success($leaveRequest, 'Leave request submitted', status: 201);
    }

    public function show(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('view', $leaveRequest);

        return ApiResponse::success($leaveRequest->load(['employee', 'leaveType', 'workflow.steps']));
    }

    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('cancel', $leaveRequest);

        $this->service->cancel($leaveRequest);

        return ApiResponse::message('Leave request cancelled');
    }

    public function approvals(Request $request): JsonResponse
    {
        $managerEmployeeId = $request->user()->employee?->id;

        $paginated = LeaveRequest::query()
            ->with(['employee', 'leaveType'])
            ->where('status', 'pending')
            ->whereHas('employee', fn ($q) => $q->where('manager_id', $managerEmployeeId))
            ->paginate((int) $request->query('per_page', 15));

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function analytics(): JsonResponse
    {
        return ApiResponse::success([
            'total_requests' => LeaveRequest::query()->count(),
            'pending' => LeaveRequest::query()->where('status', 'pending')->count(),
            'approved' => LeaveRequest::query()->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::query()->where('status', 'rejected')->count(),
            'total_quota_used' => (float) LeaveQuota::query()->sum('used_days'),
        ]);
    }

    public function policies(): JsonResponse
    {
        return ApiResponse::success(LeavePolicyModel::query()->with(['leaveType', 'department'])->get());
    }

    public function storePolicy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'rules' => ['required', 'array'],
        ]);

        $policy = LeavePolicyModel::create($data);

        return ApiResponse::success($policy, 'Policy created', status: 201);
    }

    public function updatePolicy(Request $request, LeavePolicyModel $policy): JsonResponse
    {
        $policy->update($request->validate([
            'rules' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return ApiResponse::success($policy, 'Policy updated');
    }
}
