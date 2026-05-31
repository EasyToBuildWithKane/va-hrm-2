<?php

declare(strict_types=1);

namespace Modules\Organization\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Employee\Models\Employee;

/**
 * @property int $id
 * @property string $node_type
 * @property string $reference_type
 * @property int $reference_id
 * @property string $label
 * @property bool $is_active
 */
class OrganizationNode extends Model
{
    protected $fillable = [
        'node_type',
        'reference_type',
        'reference_id',
        'label',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo(null, 'reference_type', 'reference_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function forEmployee(int $employeeId): ?self
    {
        return self::query()
            ->where('reference_type', Employee::class)
            ->where('reference_id', $employeeId)
            ->first();
    }
}
