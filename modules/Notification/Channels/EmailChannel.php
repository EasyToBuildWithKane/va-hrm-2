<?php

declare(strict_types=1);

namespace Modules\Notification\Channels;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailChannel
{
    public function send(User $user, string $subject, string $body): void
    {
        if (! $user->email) {
            return;
        }

        try {
            Mail::raw($body, function ($mail) use ($user, $subject): void {
                $mail->to($user->email)->subject($subject);
            });
        } catch (\Throwable $e) {
            Log::warning('Email send failed', ['user' => $user->id, 'error' => $e->getMessage()]);
        }
    }
}
