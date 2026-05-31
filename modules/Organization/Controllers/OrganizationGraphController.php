<?php

declare(strict_types=1);

namespace Modules\Organization\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Department\Models\Department;
use Modules\Employee\Models\Employee;
use Modules\Organization\Models\OrganizationRelationship;
use Modules\Organization\Services\OrganizationGraphService;

class OrganizationGraphController extends Controller
{
    public function __construct(private readonly OrganizationGraphService $service)
    {
    }

    public function graph(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->buildGraph($request->integer('department_id') ?: null));
    }

    public function subtree(int $nodeId, Request $request): JsonResponse
    {
        return ApiResponse::success(
            $this->service->getSubtree($nodeId, (int) $request->query('depth', 3))
        );
    }

    public function reportingChain(Employee $employee): JsonResponse
    {
        return ApiResponse::success($this->service->getReportingChain($employee->id));
    }

    public function departmentHierarchy(Department $department): JsonResponse
    {
        return ApiResponse::success([
            'department' => $department,
            'parents' => $this->ancestorChain($department),
            'children' => $department->children()->get(),
        ]);
    }

    public function storeRelationship(Request $request): JsonResponse
    {
        $data = $request->validate([
            'from_node_id' => ['required', 'exists:organization_nodes,id'],
            'to_node_id' => ['required', 'exists:organization_nodes,id'],
            'relationship_type' => ['required', 'in:REPORT_TO,MANAGE,BELONG_TO,APPROVE_FOR,WORK_WITH,MEMBER_OF'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        $relationship = OrganizationRelationship::create($data);

        return ApiResponse::success($relationship, 'Relationship created', status: 201);
    }

    public function updateRelationship(Request $request, OrganizationRelationship $relationship): JsonResponse
    {
        $relationship->update($request->validate([
            'weight' => ['sometimes', 'numeric'],
            'is_active' => ['sometimes', 'boolean'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date'],
        ]));

        return ApiResponse::success($relationship, 'Relationship updated');
    }

    public function destroyRelationship(OrganizationRelationship $relationship): JsonResponse
    {
        $relationship->delete();

        return ApiResponse::message('Relationship removed');
    }

    public function sync(): JsonResponse
    {
        $this->service->syncGraph();

        return ApiResponse::message('Organization graph resynced');
    }

    /**
     * @return array<int, Department>
     */
    private function ancestorChain(Department $department): array
    {
        $chain = [];
        $current = $department->parent;
        while ($current) {
            $chain[] = $current;
            $current = $current->parent;
        }

        return $chain;
    }
}
