<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Employee\Events\EmployeeOnboarded;
use Modules\Notification\Services\NotificationService;

class SendWelcomeNotificationListener
{
    public function __construct(private readonly NotificationService $service)
    {
    }

    public function handle(EmployeeOnboarded $event): void
    {
        $user = $event->employee->user;

        if ($user === null) {
            return;
        }

        $this->service->notify(
            user: $user,
            template: 'employee.welcome',
            context: ['name' => $event->employee->first_name],
            channels: ['in_app', 'email'],
        );
    }
}
