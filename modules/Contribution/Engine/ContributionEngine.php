<?php

declare(strict_types=1);

namespace Modules\Contribution\Engine;

use App\Exceptions\ContributionException;
use Illuminate\Database\Eloquent\Model;
use Modules\Contribution\Models\ContributionEvent;
use Modules\Contribution\Models\ContributionScore;
use Modules\Contribution\Models\ScoringRule;
use Modules\Department\Models\Department;
use Modules\Employee\Models\Employee;

class ContributionEngine
{
    public function __construct(
        private readonly ScoreCalculator $calculator,
        private readonly ScoringRuleEvaluator $ruleEvaluator,
    ) {
    }

    public function record(Employee $employee, string $eventType, ?Model $source = null, ?string $description = null): ContributionEvent
    {
        $rule = ScoringRule::activeForEvent($eventType);

        if (! $rule || ! $this->ruleEvaluator->evaluate($rule, $employee, $source)) {
            throw new ContributionException("No active rule for: {$eventType}");
        }

        $points = $this->calculator->calculate($rule, $employee);

        $event = ContributionEvent::create([
            'employee_id' => $employee->id,
            'rule_id' => $rule->id,
            'event_type' => $eventType,
            'points_earned' => $points,
            'reference_type' => $source ? $source::class : null,
            'reference_id' => $source?->getKey(),
            'description' => $description,
            'occurred_at' => now(),
        ]);

        $this->updateScore($employee);

        return $event;
    }

    public function updateScore(Employee $employee): ContributionScore
    {
        return ContributionScore::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'total_points' => $this->calculator->computeTotal($employee),
                'monthly_points' => $this->calculator->computePeriod($employee, 'month'),
                'quarterly_points' => $this->calculator->computePeriod($employee, 'quarter'),
                'last_calculated_at' => now(),
            ],
        );
    }

    public function applyAdjustment(Employee $employee, float $delta, string $reason): ContributionEvent
    {
        $rule = ScoringRule::query()->firstOrCreate(
            ['event_type' => 'manual_adjustment'],
            ['name' => 'Manual adjustment', 'base_points' => 0, 'multiplier' => 1.0, 'is_active' => true],
        );

        $event = ContributionEvent::create([
            'employee_id' => $employee->id,
            'rule_id' => $rule->id,
            'event_type' => 'manual_adjustment',
            'points_earned' => $delta,
            'description' => $reason,
            'occurred_at' => now(),
        ]);

        $this->updateScore($employee);

        return $event;
    }

    public function rebuildAllScores(): void
    {
        Employee::query()->active()->chunk(100, function ($chunk): void {
            foreach ($chunk as $employee) {
                $this->updateScore($employee);
            }
        });
    }

    public function rebuildRankings(): void
    {
        ContributionScore::query()->orderByDesc('total_points')->get()
            ->each(function (ContributionScore $score, int $index): void {
                $score->update(['rank_overall' => $index + 1]);
            });

        Department::query()->get()->each(function (Department $dept): void {
            ContributionScore::query()
                ->whereHas('employee', fn ($q) => $q->where('department_id', $dept->id))
                ->orderByDesc('total_points')
                ->get()
                ->each(function (ContributionScore $score, int $index): void {
                    $score->update(['rank_department' => $index + 1]);
                });
        });
    }
}
