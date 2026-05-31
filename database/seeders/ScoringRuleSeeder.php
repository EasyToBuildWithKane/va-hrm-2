<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Contribution\Models\ScoringRule;

class ScoringRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['name' => 'Task completed', 'event_type' => 'task_completed', 'base_points' => 10],
            ['name' => 'Project delivered', 'event_type' => 'project_delivered', 'base_points' => 50],
            ['name' => 'Overtime per hour', 'event_type' => 'overtime_contribution', 'base_points' => 5],
            ['name' => 'Peer recognition', 'event_type' => 'peer_recognition', 'base_points' => 15],
            ['name' => 'Department achievement', 'event_type' => 'dept_achievement', 'base_points' => 30],
            ['name' => 'Approval efficiency', 'event_type' => 'approval_efficiency', 'base_points' => 20],
            ['name' => 'Innovation proposal', 'event_type' => 'innovation_proposal', 'base_points' => 25],
            ['name' => 'Leave not abused', 'event_type' => 'leave_not_abused', 'base_points' => 5],
            ['name' => 'Onboarding complete', 'event_type' => 'onboarding_complete', 'base_points' => 10],
            ['name' => 'Training completed', 'event_type' => 'training_completed', 'base_points' => 20],
        ];

        foreach ($rules as $row) {
            ScoringRule::firstOrCreate(['event_type' => $row['event_type']], $row + ['is_active' => true]);
        }
    }
}
