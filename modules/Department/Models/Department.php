<?php

declare(strict_types=1);

namespace Modules\Department\Models;

use App\Concerns\HasAuditLog;
use App\Contracts\Auditable;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Models\Employee;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int|null $parent_id
 * @property int|null $manager_id
 * @property bool $is_active
 */
class Department extends BaseModel implements Auditable
{
    use HasAuditLog;
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'name',
        'code',
        'parent_id',
        'manager_id',
        'headcount_limit',
        'is_active',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected array $auditableFields = [
        'name', 'code', 'parent_id', 'manager_id', 'headcount_limit', 'is_active',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
