<?php

declare(strict_types=1);

namespace Modules\Provisioning\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Actions\OffboardEmployeeAccessAction;
use Modules\Provisioning\Actions\ProvisionAccountAction;
use Modules\Provisioning\Actions\RevokeAccessAction;
use Modules\Provisioning\Actions\SuspendAccountAction;
use Modules\Provisioning\Engine\AccessRevoker;
use Modules\Provisioning\Models\AccountProvision;
use Modules\Provisioning\Models\ProvisioningLog;
use Modules\Provisioning\Models\ProvisioningRequest;
use Modules\Provisioning\Repositories\Contracts\ProvisioningRepositoryInterface;

class ProvisioningController extends Controller
{
    public function __construct(
        private readonly ProvisioningRepositoryInterface $repository,
        private readonly ProvisionAccountAction $provisionAction,
        private readonly SuspendAccountAction $suspendAction,
        private readonly RevokeAccessAction $revokeAction,
        private readonly OffboardEmployeeAccessAction $offboardAction,
        private readonly AccessRevoker $revoker,
    ) {
    }

    public function dashboard(): JsonResponse
    {
        return ApiResponse::success([
            'total_accounts' => AccountProvision::query()->count(),
            'active_accounts' => AccountProvision::query()->where('status', 'active')->count(),
            'suspended_accounts' => AccountProvision::query()->where('status', 'suspended')->count(),
            'revoked_accounts' => AccountProvision::query()->where('status', 'revoked')->count(),
            'pending_requests' => ProvisioningRequest::query()->where('status', 'pending')->count(),
        ]);
    }

    public function accounts(Request $request): JsonResponse
    {
        $paginated = $this->repository->paginateAccounts(
            $request->only(['employee_id', 'account_type', 'status']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function storeAccount(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $provReq = ($this->provisionAction)($employee);

        return ApiResponse::success($provReq, 'Provisioning initiated', status: 201);
    }

    public function showAccount(AccountProvision $account): JsonResponse
    {
        return ApiResponse::success($account->load('employee'));
    }

    public function suspend(AccountProvision $account): JsonResponse
    {
        return ApiResponse::success(($this->suspendAction)($account), 'Account suspended');
    }

    public function activate(AccountProvision $account): JsonResponse
    {
        return ApiResponse::success($this->revoker->activate($account), 'Account activated');
    }

    public function revoke(AccountProvision $account): JsonResponse
    {
        return ApiResponse::success(($this->revokeAction)($account), 'Account revoked');
    }

    public function triggerOnboarding(Employee $employee): JsonResponse
    {
        $request = ($this->provisionAction)($employee);

        return ApiResponse::success($request, 'Onboarding triggered');
    }

    public function triggerOffboarding(Request $request, Employee $employee): JsonResponse
    {
        $data = $request->validate(['reason' => ['nullable', 'string']]);
        $provReq = ($this->offboardAction)($employee, $data['reason'] ?? null);

        return ApiResponse::success($provReq, 'Offboarding triggered');
    }

    public function logs(Employee $employee): JsonResponse
    {
        return ApiResponse::success(
            ProvisioningLog::query()->where('employee_id', $employee->id)->latest()->get()
        );
    }
}
