<?php

declare(strict_types=1);

namespace Modules\Audit\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Audit\Models\AuditLog;
use Modules\Audit\Repositories\Contracts\AuditRepositoryInterface;
use Modules\Audit\Services\AuditService;
use Modules\Employee\Models\Employee;

class AuditController extends Controller
{
    public function __construct(
        private readonly AuditRepositoryInterface $repository,
        private readonly AuditService $service,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->repository->paginate(
            $request->only(['event', 'module', 'performed_by', 'from', 'to']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success($paginated->items(), meta: [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
        ]);
    }

    public function show(AuditLog $log): JsonResponse
    {
        return ApiResponse::success($log);
    }

    public function diff(AuditLog $log): JsonResponse
    {
        return ApiResponse::success($this->service->buildDiff($log));
    }

    public function forEmployee(Employee $employee): JsonResponse
    {
        return ApiResponse::success(
            $this->repository->forAuditable(Employee::class, $employee->id)
        );
    }

    public function forWorkflow(int $workflowId): JsonResponse
    {
        $logs = AuditLog::query()
            ->whereJsonContains('context->workflow_id', $workflowId)
            ->latest('created_at')
            ->get();

        return ApiResponse::success($logs);
    }

    public function forProvisioning(Employee $employee): JsonResponse
    {
        $logs = AuditLog::query()
            ->where('auditable_type', Employee::class)
            ->where('auditable_id', $employee->id)
            ->whereIn('event', ['activated', 'deactivated', 'assigned', 'revoked'])
            ->latest('created_at')
            ->get();

        return ApiResponse::success($logs);
    }

    public function permissions(): JsonResponse
    {
        $logs = AuditLog::query()
            ->where('auditable_type', 'like', '%Permission%')
            ->orWhere('auditable_type', 'like', '%Role%')
            ->latest('created_at')
            ->paginate(15);

        return ApiResponse::success($logs->items(), meta: [
            'total' => $logs->total(),
            'per_page' => $logs->perPage(),
            'current_page' => $logs->currentPage(),
        ]);
    }

    public function export(): JsonResponse
    {
        return ApiResponse::message('Export queued. You will receive a download link by notification.');
    }
}
