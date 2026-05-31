<?php

declare(strict_types=1);

namespace Modules\Contribution\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    protected $fillable = [
        'name', 'event_type', 'base_points',
        'multiplier', 'conditions', 'is_active',
    ];

    protected $casts = [
        'base_points' => 'decimal:2',
        'multiplier' => 'decimal:2',
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    public static function activeForEvent(string $eventType): ?self
    {
        return self::query()
            ->where('event_type', $eventType)
            ->where('is_active', true)
            ->first();
    }
}
