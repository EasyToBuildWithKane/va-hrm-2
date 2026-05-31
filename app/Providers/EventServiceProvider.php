<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Approval\Events\ApprovalEscalated;
use Modules\Approval\Events\ApprovalRejected;
use Modules\Approval\Events\ApprovalStepCompleted;
use Modules\Approval\Events\ApprovalWorkflowCompleted;
use Modules\Approval\Listeners\NotifyRequestorListener;
use Modules\Approval\Listeners\UpdateRequestStatusListener;
use Modules\Audit\Listeners\AuditApprovalDecisionListener;
use Modules\Employee\Events\EmployeeCreated;
use Modules\Employee\Events\EmployeeOffboarded;
use Modules\Employee\Events\EmployeeOnboarded;
use Modules\Employee\Events\EmployeeTerminated;
use Modules\Employee\Events\EmployeeUpdated;
use Modules\Employee\Listeners\TriggerOffboardingOnTermination;
use Modules\Employee\Listeners\TriggerProvisioningOnCreate;
use Modules\Employee\Listeners\UpdateOrganizationGraphOnChange;
use Modules\Notification\Listeners\SendWelcomeNotificationListener;
use Modules\Provisioning\Events\AccessRevoked;
use Modules\Provisioning\Events\AccountProvisioned;
use Modules\Provisioning\Events\AccountSuspended;
use Modules\Provisioning\Listeners\ExecuteProvisioningOnApproval;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Employee lifecycle
        EmployeeCreated::class => [
            TriggerProvisioningOnCreate::class,
            UpdateOrganizationGraphOnChange::class,
        ],
        EmployeeUpdated::class => [
            UpdateOrganizationGraphOnChange::class,
        ],
        EmployeeTerminated::class => [
            TriggerOffboardingOnTermination::class,
            UpdateOrganizationGraphOnChange::class,
        ],
        EmployeeOnboarded::class => [
            SendWelcomeNotificationListener::class,
        ],
        EmployeeOffboarded::class => [],

        // Approval pipeline
        ApprovalStepCompleted::class => [
            AuditApprovalDecisionListener::class,
        ],
        ApprovalWorkflowCompleted::class => [
            ExecuteProvisioningOnApproval::class,
            UpdateRequestStatusListener::class,
            NotifyRequestorListener::class,
        ],
        ApprovalRejected::class => [
            UpdateRequestStatusListener::class,
            NotifyRequestorListener::class,
        ],
        ApprovalEscalated::class => [],

        // Provisioning
        AccountProvisioned::class => [],
        AccountSuspended::class => [],
        AccessRevoked::class => [],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
