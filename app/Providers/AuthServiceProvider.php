<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Approval\Policies\ApprovalPolicy;
use Modules\Department\Models\Department;
use Modules\Department\Policies\DepartmentPolicy;
use Modules\Employee\Models\Employee;
use Modules\Employee\Policies\EmployeePolicy;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Policies\LeavePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Department::class => DepartmentPolicy::class,
        ApprovalWorkflow::class => ApprovalPolicy::class,
        LeaveRequest::class => LeavePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
