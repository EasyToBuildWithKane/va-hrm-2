<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Employee\Models\Employee;
use Modules\Permission\Models\PermissionDelegation;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $email
 * @property string|null $status
 */
class User extends Authenticatable
{

    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasUlid;
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'ulid',
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function isManagerOf(int $departmentId): bool
    {
        return $this->employee?->department_id === $departmentId
            && $this->hasRole(config('permission_hrm.roles.department_manager'));
    }

    public function hasActiveDelegationFor(string $permission): bool
    {
        return PermissionDelegation::activeForUser($this->id)
            ->where('permission', $permission)
            ->exists();
    }
}
