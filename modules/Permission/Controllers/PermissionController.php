<?php

declare(strict_types=1);

namespace Modules\Permission\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Permission\Services\PermissionService;
use Modules\Permission\Services\RoleService;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionService $service,
        private readonly RoleService $roleService,
    ) {
    }

    public function userPermissions(User $user): JsonResponse
    {
        return ApiResponse::success([
            'user_id' => $user->id,
            'roles' => $user->getRoleNames(),
            'permissions' => $this->service->effectivePermissions($user),
        ]);
    }

    public function assignRole(Request $request, User $user): JsonResponse
    {
        $data = $request->validate(['role' => ['required', 'string', 'exists:roles,name']]);
        $user = $this->roleService->assignToUser($user, $data['role']);

        return ApiResponse::success([
            'user_id' => $user->id,
            'roles' => $user->getRoleNames(),
        ], 'Role assigned');
    }

    public function revokeRole(User $user, string $role): JsonResponse
    {
        $this->roleService->revokeFromUser($user, $role);

        return ApiResponse::message('Role revoked');
    }
}
