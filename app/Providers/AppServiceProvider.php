<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            if (str_starts_with($modelName, 'Modules\\')) {
                return preg_replace(
                    '/^Modules\\\\(.+)\\\\Models\\\\(.+)$/',
                    'Modules\\\\$1\\\\Database\\\\Factories\\\\$2Factory',
                    $modelName,
                );
            }

            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });

        Relation::enforceMorphMap([
            'employee' => \Modules\Employee\Models\Employee::class,
            'department' => \Modules\Department\Models\Department::class,
            'leave_request' => \Modules\Leave\Models\LeaveRequest::class,
            'workflow_request' => \Modules\Request\Models\WorkflowRequest::class,
            'provisioning_request' => \Modules\Provisioning\Models\ProvisioningRequest::class,
            'approval_workflow' => \Modules\Approval\Models\ApprovalWorkflow::class,
            'attendance_correction' => \Modules\Attendance\Models\AttendanceCorrection::class,
            'score_adjustment_request' => \Modules\Contribution\Models\ScoreAdjustmentRequest::class,
            'user' => \App\Models\User::class,
        ]);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
