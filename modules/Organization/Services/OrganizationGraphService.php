<?php

declare(strict_types=1);

namespace Modules\Organization\Services;

use Illuminate\Support\Facades\DB;
use Modules\Department\Models\Department;
use Modules\Employee\Models\Employee;
use Modules\Organization\Graph\GraphNodeFactory;
use Modules\Organization\Graph\GraphRelationshipResolver;
use Modules\Organization\Graph\OrganizationGraphBuilder;
use Modules\Organization\Models\OrganizationNode;
use Modules\Organization\Models\OrganizationRelationship;

class OrganizationGraphService
{
    public function __construct(private readonly OrganizationGraphBuilder $builder)
    {
    }

    /**
     * @return array{nodes: array, edges: array, meta: array}
     */
    public function buildGraph(?int $departmentId = null): array
    {
        return $this->builder->build($departmentId);
    }

    /**
     * @return array{nodes: array, edges: array}
     */
    public function getSubtree(int $nodeId, int $depth = 3): array
    {
        $nodeIds = $this->traverse($nodeId, $depth);

        $nodes = OrganizationNode::query()->whereIn('id', $nodeIds)->get();
        $edges = OrganizationRelationship::query()
            ->whereIn('from_node_id', $nodeIds)
            ->whereIn('to_node_id', $nodeIds)
            ->get();

        return [
            'nodes' => GraphNodeFactory::buildMany($nodes),
            'edges' => GraphRelationshipResolver::build($edges, $nodes),
        ];
    }

    /**
     * @return array<int, Employee>
     */
    public function getReportingChain(int $employeeId): array
    {
        $chain = [];
        $current = Employee::query()->find($employeeId);

        while ($current && $current->manager_id) {
            $chain[] = $current;
            $current = $current->manager;
        }

        if ($current) {
            $chain[] = $current;
        }

        return $chain;
    }

    public function syncGraph(): void
    {
        DB::transaction(function (): void {
            Employee::query()->active()->each(function (Employee $employee): void {
                $this->syncEmployee($employee);
            });

            Department::query()->where('is_active', true)->each(function (Department $department): void {
                $this->syncDepartment($department);
            });
        });
    }

    public function syncEmployee(Employee $employee): OrganizationNode
    {
        $node = OrganizationNode::updateOrCreate(
            ['reference_type' => Employee::class, 'reference_id' => $employee->id],
            [
                'node_type' => 'employee',
                'label' => trim("{$employee->first_name} {$employee->last_name}"),
                'is_active' => in_array($employee->employment_status?->value ?? $employee->employment_status, ['active', 'on_leave'], true),
                'metadata' => [
                    'employee_number' => $employee->employee_number,
                    'department_id' => $employee->department_id,
                    'position_id' => $employee->position_id,
                ],
            ],
        );

        if ($employee->manager_id) {
            $managerNode = OrganizationNode::forEmployee($employee->manager_id);
            if ($managerNode) {
                OrganizationRelationship::updateOrCreate(
                    ['from_node_id' => $node->id, 'to_node_id' => $managerNode->id, 'relationship_type' => 'REPORT_TO'],
                    ['is_active' => true],
                );
            }
        }

        $deptNode = OrganizationNode::query()
            ->where('reference_type', Department::class)
            ->where('reference_id', $employee->department_id)
            ->first();

        if ($deptNode) {
            OrganizationRelationship::updateOrCreate(
                ['from_node_id' => $node->id, 'to_node_id' => $deptNode->id, 'relationship_type' => 'BELONG_TO'],
                ['is_active' => true],
            );
        }

        return $node;
    }

    public function syncDepartment(Department $department): OrganizationNode
    {
        return OrganizationNode::updateOrCreate(
            ['reference_type' => Department::class, 'reference_id' => $department->id],
            [
                'node_type' => 'department',
                'label' => $department->name,
                'is_active' => $department->is_active,
                'metadata' => [
                    'code' => $department->code,
                    'parent_id' => $department->parent_id,
                ],
            ],
        );
    }

    /**
     * @return array<int, int>
     */
    private function traverse(int $startNodeId, int $maxDepth): array
    {
        $visited = [$startNodeId => true];
        $queue = [[$startNodeId, 0]];

        while ($queue !== []) {
            [$nodeId, $depth] = array_shift($queue);

            if ($depth >= $maxDepth) {
                continue;
            }

            $neighbours = OrganizationRelationship::query()
                ->active()
                ->where(function ($q) use ($nodeId): void {
                    $q->where('from_node_id', $nodeId)->orWhere('to_node_id', $nodeId);
                })
                ->get(['from_node_id', 'to_node_id']);

            foreach ($neighbours as $rel) {
                foreach ([$rel->from_node_id, $rel->to_node_id] as $candidate) {
                    if (! isset($visited[$candidate])) {
                        $visited[$candidate] = true;
                        $queue[] = [$candidate, $depth + 1];
                    }
                }
            }
        }

        return array_keys($visited);
    }
}
