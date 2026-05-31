<?php

declare(strict_types=1);

namespace Modules\Contribution\Services;

use Illuminate\Support\Str;
use Modules\Contribution\Engine\ContributionEngine;
use Modules\Contribution\Models\ContributionScore;
use Modules\Contribution\Models\ScoreAdjustmentRequest;
use Modules\Employee\Models\Employee;

class ContributionService
{
    public function __construct(private readonly ContributionEngine $engine)
    {
    }

    /**
     * @return array<string, int|float>
     */
    public function dashboard(): array
    {
        return [
            'total_employees_scored' => ContributionScore::query()->count(),
            'avg_total_points' => (float) ContributionScore::query()->avg('total_points'),
            'top_score' => (float) ContributionScore::query()->max('total_points'),
        ];
    }

    public function submitAdjustment(Employee $employee, float $delta, string $reason, int $requestedBy): ScoreAdjustmentRequest
    {
        return ScoreAdjustmentRequest::create([
            'ulid' => (string) Str::ulid(),
            'employee_id' => $employee->id,
            'adjustment_points' => $delta,
            'reason' => $reason,
            'status' => 'pending',
            'requested_by' => $requestedBy,
        ]);
    }
}
