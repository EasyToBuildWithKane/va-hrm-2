<?php

declare(strict_types=1);

namespace Modules\Organization\Graph;

use Illuminate\Support\Collection;
use Modules\Organization\Models\OrganizationRelationship;

class GraphRelationshipResolver
{
    /**
     * @param  Collection<int, OrganizationRelationship>  $edges
     * @param  Collection<int, \Modules\Organization\Models\OrganizationNode>  $nodes
     * @return array<int, array<string, mixed>>
     */
    public static function build(Collection $edges, Collection $nodes): array
    {
        $nodeIndex = $nodes->keyBy('id');

        return $edges->map(function (OrganizationRelationship $edge) use ($nodeIndex): array {
            $from = $nodeIndex[$edge->from_node_id] ?? null;
            $to = $nodeIndex[$edge->to_node_id] ?? null;

            return [
                'id' => 'rel_'.$edge->id,
                'source' => $from ? $from->node_type.'_'.$from->id : null,
                'target' => $to ? $to->node_type.'_'.$to->id : null,
                'type' => $edge->relationship_type,
                'label' => str_replace('_', ' ', ucwords(strtolower($edge->relationship_type), '_')),
                'weight' => (float) $edge->weight,
            ];
        })->all();
    }
}
