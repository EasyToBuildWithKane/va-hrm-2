<?php

declare(strict_types=1);

namespace Modules\Audit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

/**
 * Immutable audit log entry.
 *
 * @property string $ulid
 * @property string $auditable_type
 * @property int $auditable_id
 * @property string $event
 * @property array|null $old_values
 * @property array|null $new_values
 * @property array|null $changed_fields
 * @property int $performed_by
 */
class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ulid',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'changed_fields',
        'performed_by',
        'ip_address',
        'user_agent',
        'context',
        'payroll_sensitive',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'context' => 'array',
        'payroll_sensitive' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
