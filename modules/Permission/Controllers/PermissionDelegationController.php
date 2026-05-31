<?php

declare(strict_types=1);

namespace Modules\Permission\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Permission\Models\PermissionDelegation;
use Modules\Permission\Services\PermissionService;

class PermissionDelegationController extends Controller
{
    public function __construct(private readonly PermissionService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $delegations = PermissionDelegation::query()
            ->when($request->user(), fn ($q, $u) => $q->where('delegated_to', $u->id))
            ->with(['delegator', 'delegatee'])
            ->latest()
            ->paginate((int) $request->query('per_page', 15));

        return ApiResponse::success($delegations->items(), meta: [
            'current_page' => $delegations->currentPage(),
            'per_page' => $delegations->perPage(),
            'total' => $delegations->total(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'delegated_to' => ['required', 'exists:users,id'],
            'delegation_type' => ['required', 'in:approval,role,permission'],
            'permission' => ['nullable', 'string'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'reason' => ['required', 'string'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after:valid_from'],
        ]);

        $delegation = $this->service->delegate(
            from: $request->user(),
            to: User::findOrFail($data['delegated_to']),
            delegationType: $data['delegation_type'],
            reason: $data['reason'],
            validFrom: Carbon::parse($data['valid_from']),
            validUntil: Carbon::parse($data['valid_until']),
            permission: $data['permission'] ?? null,
            roleId: $data['role_id'] ?? null,
        );

        return ApiResponse::success($delegation, 'Delegation created', status: 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->revokeDelegation($id);

        return ApiResponse::message('Delegation revoked');
    }
}
