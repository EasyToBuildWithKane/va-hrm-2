<?php

declare(strict_types=1);

namespace Modules\Request\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Request\Actions\CancelRequestAction;
use Modules\Request\Actions\SubmitRequestAction;
use Modules\Request\DTOs\SubmitRequestDTO;
use Modules\Request\Models\WorkflowRequest;
use Modules\Request\Repositories\Contracts\RequestRepositoryInterface;

class RequestController extends Controller
{
    public function __construct(
        private readonly RequestRepositoryInterface $repository,
        private readonly SubmitRequestAction $submitAction,
        private readonly CancelRequestAction $cancelAction,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->repository->paginate(
            $request->only(['request_type', 'status', 'employee_id', 'from', 'to']),
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
        $request->validate([
            'request_type' => ['required', 'in:'.implode(',', config('workflow.allowed_workflow_types', []))],
            'employee_id' => ['required', 'exists:employees,id'],
            'payload' => ['required', 'array'],
            'justification' => ['nullable', 'string'],
        ]);

        $workflowRequest = ($this->submitAction)(SubmitRequestDTO::fromRequest($request), $request->user());

        return ApiResponse::success($workflowRequest, 'Request submitted', status: 201);
    }

    public function show(WorkflowRequest $request): JsonResponse
    {
        return ApiResponse::success(
            $request->load(['employee', 'workflow.steps', 'equipment', 'reimbursement', 'softwareAccess', 'account', 'salaryAdjustment'])
        );
    }

    public function destroy(WorkflowRequest $request): JsonResponse
    {
        ($this->cancelAction)($request);

        return ApiResponse::message('Request cancelled');
    }
}
