<?php

declare(strict_types=1);

namespace Modules\Notification\Channels;

use App\Models\User;
use Modules\Notification\Models\UserNotification;

class InAppChannel
{
    /**
     * @param  array<string, mixed>|null  $payload
     */
    public function send(User $user, string $type, string $title, string $body, ?array $payload = null, ?string $actionUrl = null): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'channel' => 'in_app',
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'payload' => $payload,
            'action_url' => $actionUrl,
        ]);
    }
}
