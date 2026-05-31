<?php

declare(strict_types=1);

namespace Modules\Organization\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationRelationship extends Model
{
    protected $fillable = [
        'from_node_id', 'to_node_id', 'relationship_type',
        'weight', 'is_active', 'valid_from', 'valid_until',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(OrganizationNode::class, 'from_node_id');
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(OrganizationNode::class, 'to_node_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
