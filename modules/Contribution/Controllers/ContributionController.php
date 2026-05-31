<?php

declare(strict_types=1);

namespace Modules\Contribution\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Contribution\Models\ContributionScore;
use Modules\Contribution\Models\ScoringRule;
use Modules\Contribution\Repositories\Contracts\ContributionRepositoryInterface;
use Modules\Contribution\Services\ContributionService;
use Modules\Employee\Models\Employee;

class ContributionController extends Controller
{
    public function __construct(
        private readonly ContributionService $service,
        private readonly ContributionRepositoryInterface $repository,
    ) {
    }

    public function dashboard(): JsonResponse
    {
        return ApiResponse::success($this->service->dashboard());
    }

    public function ranking(Request $request): JsonResponse
    {
        $paginated = $this->repository->ranking(
            (int) $request->query('per_page', 15),
            $request->integer('department_id') ?: null,
        );

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function employeeScore(Employee $employee): JsonResponse
    {
        $score = ContributionScore::query()->where('employee_id', $employee->id)->first();

        return ApiResponse::success($score ?? ['employee_id' => $employee->id, 'total_points' => 0]);
    }

    public function employeeHistory(Employee $employee): JsonResponse
    {
        return ApiResponse::success($this->repository->eventsForEmployee($employee->id));
    }

    public function adjustments(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'adjustment_points' => ['required', 'numeric'],
            'reason' => ['required', 'string'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

        $adj = $this->service->submitAdjustment(
            $employee,
            (float) $data['adjustment_points'],
            $data['reason'],
            $request->user()->id,
        );

        return ApiResponse::success($adj, 'Adjustment requested', status: 201);
    }

    public function rules(): JsonResponse
    {
        return ApiResponse::success(ScoringRule::query()->orderBy('event_type')->get());
    }

    public function storeRule(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'event_type' => ['required', 'string', 'unique:scoring_rules,event_type'],
            'base_points' => ['required', 'numeric'],
            'multiplier' => ['nullable', 'numeric'],
            'conditions' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        return ApiResponse::success(ScoringRule::create($data), 'Rule created', status: 201);
    }

    public function updateRule(Request $request, ScoringRule $rule): JsonResponse
    {
        $rule->update($request->validate([
            'name' => ['sometimes', 'string'],
            'base_points' => ['sometimes', 'numeric'],
            'multiplier' => ['sometimes', 'numeric'],
            'conditions' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return ApiResponse::success($rule, 'Rule updated');
    }
}
