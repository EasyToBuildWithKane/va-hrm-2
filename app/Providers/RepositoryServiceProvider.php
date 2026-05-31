<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Approval\Repositories\ApprovalRepository;
use Modules\Approval\Repositories\Contracts\ApprovalRepositoryInterface;
use Modules\Attendance\Repositories\AttendanceRepository;
use Modules\Attendance\Repositories\Contracts\AttendanceRepositoryInterface;
use Modules\Audit\Repositories\AuditRepository;
use Modules\Audit\Repositories\Contracts\AuditRepositoryInterface;
use Modules\Contribution\Repositories\ContributionRepository;
use Modules\Contribution\Repositories\Contracts\ContributionRepositoryInterface;
use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;
use Modules\Department\Repositories\DepartmentRepository;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use Modules\Employee\Repositories\EmployeeRepository;
use Modules\Leave\Repositories\Contracts\LeaveRepositoryInterface;
use Modules\Leave\Repositories\LeaveRepository;
use Modules\Provisioning\Repositories\Contracts\ProvisioningRepositoryInterface;
use Modules\Provisioning\Repositories\ProvisioningRepository;
use Modules\Request\Repositories\Contracts\RequestRepositoryInterface;
use Modules\Request\Repositories\RequestRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        EmployeeRepositoryInterface::class => EmployeeRepository::class,
        DepartmentRepositoryInterface::class => DepartmentRepository::class,
        ApprovalRepositoryInterface::class => ApprovalRepository::class,
        AttendanceRepositoryInterface::class => AttendanceRepository::class,
        AuditRepositoryInterface::class => AuditRepository::class,
        ContributionRepositoryInterface::class => ContributionRepository::class,
        LeaveRepositoryInterface::class => LeaveRepository::class,
        ProvisioningRepositoryInterface::class => ProvisioningRepository::class,
        RequestRepositoryInterface::class => RequestRepository::class,
    ];
}
