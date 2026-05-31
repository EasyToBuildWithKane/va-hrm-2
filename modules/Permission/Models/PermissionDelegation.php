<?php

declare(strict_types=1);

namespace Modules\Permission\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Permission\Database\Factories\PermissionDelegationFactory;
use App\Models\User;

/**
 * @property int $id
 * @property string $ulid
 * @property int $delegated_by
 * @property int $delegated_to
 * @property string $delegation_type
 * @property string|null $permission
 * @property \Illuminate\Support\Carbon $valid_from
 * @property \Illuminate\Support\Carbon $valid_until
 */
class PermissionDelegation extends BaseModel
{
    protected $fillable = [
        'ulid',
        'delegated_by',
        'delegated_to',
        'delegation_type',
        'role_id',
        'permission',
        'scope_type',
        'scope_id',
        'valid_from',
        'valid_until',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    protected static function newFactory(): PermissionDelegationFactory
    {
        return PermissionDelegationFactory::new();
    }

    public function delegator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_by');
    }

    public function delegatee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_to');
    }

    public function scopeActiveForUser(Builder $query, int $userId): Builder
    {
        return $query->where('delegated_to', $userId)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }
}
