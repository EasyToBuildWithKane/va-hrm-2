<?php

declare(strict_types=1);

namespace Modules\Organization\Graph;

use Illuminate\Support\Collection;
use Modules\Organization\Models\OrganizationNode;

class GraphNodeFactory
{
    /**
     * @return array<string, mixed>
     */
    public static function build(OrganizationNode $node): array
    {
        return [
            'id' => $node->node_type.'_'.$node->id,
            'type' => $node->node_type,
            'label' => $node->label,
            'data' => array_merge([
                'node_id' => $node->id,
                'reference_id' => $node->reference_id,
                'is_active' => $node->is_active,
            ], $node->metadata ?? []),
        ];
    }

    /**
     * @param  Collection<int, OrganizationNode>  $nodes
     * @return array<int, array<string, mixed>>
     */
    public static function buildMany(Collection $nodes): array
    {
        return $nodes->map(fn (OrganizationNode $n) => self::build($n))->all();
    }
}
