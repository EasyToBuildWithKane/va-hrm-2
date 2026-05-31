<?php

declare(strict_types=1);

namespace Modules\Permission\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Permission\Models\Role;
use Modules\Permission\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(private readonly RoleService $service)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success($this->service->list());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $role = $this->service->create($data['name'], $data['permissions'] ?? []);

        return ApiResponse::success($role, 'Role created', status: 201);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string'],
        ]);

        if (isset($data['name'])) {
            $role->update(['name' => $data['name']]);
        }
        if (array_key_exists('permissions', $data)) {
            $this->service->syncPermissions($role, $data['permissions']);
        }

        return ApiResponse::success($role->fresh('permissions'), 'Role updated');
    }

    public function sync(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string'],
        ]);

        $role = $this->service->syncPermissions($role, $data['permissions']);

        return ApiResponse::success($role, 'Permissions synced');
    }

    public function matrix(): JsonResponse
    {
        return ApiResponse::success($this->service->buildMatrix());
    }
}
