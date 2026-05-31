<?php

declare(strict_types=1);

namespace Modules\Organization\Graph;

use Modules\Organization\Models\OrganizationNode;
use Modules\Organization\Models\OrganizationRelationship;

class OrganizationGraphBuilder
{
    /**
     * @return array{nodes: array, edges: array, meta: array}
     */
    public function build(?int $departmentId = null): array
    {
        $nodes = OrganizationNode::query()->active()->get();

        $edges = OrganizationRelationship::query()
            ->active()
            ->whereIn('from_node_id', $nodes->pluck('id'))
            ->whereIn('to_node_id', $nodes->pluck('id'))
            ->get();

        return [
            'nodes' => GraphNodeFactory::buildMany($nodes),
            'edges' => GraphRelationshipResolver::build($edges, $nodes),
            'meta' => [
                'total_nodes' => $nodes->count(),
                'total_edges' => $edges->count(),
                'filtered_by_department' => $departmentId,
            ],
        ];
    }
}
