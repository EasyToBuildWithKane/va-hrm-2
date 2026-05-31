<?php

declare(strict_types=1);

namespace Modules\Notification\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackChannel
{
    public function send(string $webhookUrl, string $text): void
    {
        if (! $webhookUrl) {
            return;
        }

        try {
            Http::timeout(5)->post($webhookUrl, ['text' => $text]);
        } catch (\Throwable $e) {
            Log::warning('Slack send failed', ['error' => $e->getMessage()]);
        }
    }
}
