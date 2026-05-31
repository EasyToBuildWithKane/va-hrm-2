<?php

declare(strict_types=1);

namespace Modules\Provisioning\Engine;

use App\Enums\AuditEvent;
use Illuminate\Support\Facades\DB;
use Modules\Audit\Services\AuditService;
use Modules\Notification\Services\NotificationService;
use Modules\Provisioning\Events\AccountProvisioned;
use Modules\Provisioning\Models\ProvisioningLog;
use Modules\Provisioning\Models\ProvisioningRequest;

class ProvisioningEngine
{
    public function __construct(
        private readonly EmailProvisioner $emailProvisioner,
        private readonly AccountProvisioner $accountProvisioner,
        private readonly AccessRevoker $accessRevoker,
        private readonly AuditService $auditService,
        private readonly NotificationService $notificationService,
    ) {
    }

    public function execute(ProvisioningRequest $request): void
    {
        match ($request->type) {
            'onboarding' => $this->executeOnboarding($request),
            'offboarding' => $this->executeOffboarding($request),
            'access_change' => $this->executeAccessChange($request),
            'license_assign' => $this->executeLicenseAssign($request),
            default => null,
        };

        $request->update(['status' => 'active', 'processed_at' => now()]);
    }

    private function executeOnboarding(ProvisioningRequest $request): void
    {
        $employee = $request->employee;

        DB::transaction(function () use ($employee, $request): void {
            $email = $this->emailProvisioner->provision($employee);
            $account = $this->accountProvisioner->provision($employee);

            $this->log($request, 'create_email', $email->account_identifier);
            $this->log($request, 'create_account', $account->account_identifier);

            event(new AccountProvisioned($email));
            event(new AccountProvisioned($account));

            $this->auditService->log(
                $employee,
                AuditEvent::ACTIVATED,
                null,
                ['email' => $email->account_identifier, 'account' => $account->account_identifier],
            );
        });

        if ($employee->user) {
            $this->notificationService->notify(
                user: $employee->user,
                template: 'provisioning.completed',
                channels: ['in_app', 'email'],
            );
        }
    }

    private function executeOffboarding(ProvisioningRequest $request): void
    {
        $employee = $request->employee;

        DB::transaction(function () use ($employee, $request): void {
            $this->accessRevoker->revokeAll($employee);
            $this->log($request, 'revoke_all_access', "employee:{$employee->id}");

            $this->auditService->log(
                $employee,
                AuditEvent::DEACTIVATED,
                null,
                ['reason' => 'employee_offboarding'],
            );
        });
    }

    private function executeAccessChange(ProvisioningRequest $request): void
    {
        $this->log($request, 'access_change', "employee:{$request->employee_id}");
    }

    private function executeLicenseAssign(ProvisioningRequest $request): void
    {
        $this->log($request, 'license_assign', json_encode($request->metadata ?? []));
    }

    private function log(ProvisioningRequest $request, string $action, ?string $subject = null, string $result = 'success'): void
    {
        ProvisioningLog::create([
            'provisioning_request_id' => $request->id,
            'employee_id' => $request->employee_id,
            'action' => $action,
            'subject' => $subject,
            'result' => $result,
        ]);
    }
}
