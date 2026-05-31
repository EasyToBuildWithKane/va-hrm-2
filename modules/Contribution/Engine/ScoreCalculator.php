<?php

declare(strict_types=1);

namespace Modules\Contribution\Engine;

use Modules\Contribution\Models\ContributionEvent;
use Modules\Contribution\Models\ScoringRule;
use Modules\Employee\Models\Employee;

class ScoreCalculator
{
    public function calculate(ScoringRule $rule, Employee $employee): float
    {
        $weight = (float) (config('contribution.weights.'.$rule->event_type, 1.0));
        $points = (float) $rule->base_points * (float) $rule->multiplier * $weight;

        $daily = ContributionEvent::query()
            ->where('employee_id', $employee->id)
            ->whereDate('occurred_at', now()->toDateString())
            ->sum('points_earned');

        $cap = (float) config('contribution.caps.daily_max', 100);

        if ($daily + $points > $cap) {
            return max(0.0, $cap - (float) $daily);
        }

        return round($points, 2);
    }

    public function computeTotal(Employee $employee): float
    {
        $decayEnabled = (bool) config('contribution.decay.enabled', true);
        $halfLife = (int) config('contribution.decay.half_life_days', 180);

        $events = ContributionEvent::query()
            ->where('employee_id', $employee->id)
            ->get(['points_earned', 'occurred_at']);

        if (! $decayEnabled || $halfLife <= 0) {
            return (float) $events->sum('points_earned');
        }

        $total = 0.0;
        foreach ($events as $event) {
            $ageDays = max(0, $event->occurred_at->diffInDays(now()));
            $multiplier = 2 ** (-$ageDays / $halfLife);
            $total += (float) $event->points_earned * $multiplier;
        }

        return round($total, 2);
    }

    public function computePeriod(Employee $employee, string $period): float
    {
        $start = match ($period) {
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            default => now()->startOfYear(),
        };

        return (float) ContributionEvent::query()
            ->where('employee_id', $employee->id)
            ->where('occurred_at', '>=', $start)
            ->sum('points_earned');
    }
}
