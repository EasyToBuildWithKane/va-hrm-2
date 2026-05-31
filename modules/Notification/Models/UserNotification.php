<?php

declare(strict_types=1);

namespace Modules\Notification\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $channel
 * @property string $type
 * @property string $title
 * @property string $body
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $read_at
 */
class UserNotification extends BaseModel
{
    protected $fillable = [
        'ulid',
        'user_id',
        'channel',
        'type',
        'title',
        'body',
        'payload',
        'action_url',
        'read_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
