<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use App\Concerns\HasAuditLog;
use App\Contracts\Auditable;
use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Department\Models\Department;
use Modules\Department\Models\Position;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $employee_number
 * @property int $department_id
 * @property int $position_id
 * @property int|null $manager_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property EmploymentType|string $employment_type
 * @property EmploymentStatus|string $employment_status
 */
class Employee extends BaseModel implements Auditable
{
    use HasAuditLog;
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'user_id', 'employee_number',
        'department_id', 'position_id', 'manager_id',
        'first_name', 'last_name', 'email', 'phone',
        'employment_type', 'employment_status',
        'join_date', 'probation_end_date', 'termination_date',
        'onboarding_status', 'offboarding_status',
        'salary', 'bank_account_number',
        'metadata', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'join_date' => 'date',
        'probation_end_date' => 'date',
        'termination_date' => 'date',
        'metadata' => 'array',
        'salary' => 'decimal:2',
        'employment_status' => EmploymentStatus::class,
        'employment_type' => EmploymentType::class,
    ];

    protected $hidden = [
        'salary',
        'bank_account_number',
    ];

    protected array $auditableFields = [
        'first_name', 'last_name', 'email', 'phone',
        'department_id', 'position_id', 'manager_id',
        'employment_status', 'employment_type',
        'join_date', 'termination_date',
        'salary',
    ];

    protected array $sensitiveFields = [
        'salary',
        'bank_account_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    public function directReports(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function timeline(): HasMany
    {
        return $this->hasMany(EmployeeTimeline::class)->orderByDesc('occurred_at');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('employment_status', EmploymentStatus::ACTIVE->value);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
