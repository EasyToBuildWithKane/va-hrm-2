<?php

declare(strict_types=1);

namespace Modules\Notification\Services;

use App\Models\User;
use Modules\Notification\Channels\EmailChannel;
use Modules\Notification\Channels\InAppChannel;
use Modules\Notification\Models\UserNotification;
use Modules\Notification\Templates\TemplateRegistry;

class NotificationService
{
    public function __construct(
        private readonly InAppChannel $inApp,
        private readonly EmailChannel $email,
    ) {
    }

    /**
     * @param  array<string, mixed>  $context
     * @param  array<int, string>  $channels
     */
    public function notify(User $user, string $template, array $context = [], array $channels = ['in_app']): void
    {
        $rendered = TemplateRegistry::render($template, $context);

        if (in_array('in_app', $channels, true)) {
            $this->inApp->send(
                user: $user,
                type: $template,
                title: $rendered['title'],
                body: $rendered['body'],
                payload: $context,
                actionUrl: $context['action_url'] ?? null,
            );
        }

        if (in_array('email', $channels, true)) {
            $this->email->send($user, $rendered['title'], $rendered['body']);
        }
    }

    /**
     * @param  iterable<int, User>  $users
     * @param  array<string, mixed>  $context
     */
    public function notifyMany(iterable $users, string $template, array $context = [], array $channels = ['in_app']): void
    {
        foreach ($users as $user) {
            $this->notify($user, $template, $context, $channels);
        }
    }

    public function markRead(UserNotification $notification): UserNotification
    {
        $notification->update(['read_at' => now()]);

        return $notification->fresh();
    }
}
