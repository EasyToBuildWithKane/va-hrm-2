<?php

declare(strict_types=1);

namespace Modules\Notification\Templates;

class TemplateRegistry
{
    /**
     * @param  array<string, mixed>  $context
     * @return array{title: string, body: string}
     */
    public static function render(string $template, array $context = []): array
    {
        return match ($template) {
            'approval.requested' => [
                'title' => 'New approval request',
                'body' => sprintf('You have a new %s waiting for your approval.', $context['workflow_type'] ?? 'request'),
            ],
            'approval.completed' => [
                'title' => 'Your request was approved',
                'body' => sprintf('Your %s has been fully approved.', $context['workflow_type'] ?? 'request'),
            ],
            'approval.rejected' => [
                'title' => 'Your request was rejected',
                'body' => sprintf('Your %s was rejected. Reason: %s', $context['workflow_type'] ?? 'request', $context['reason'] ?? 'N/A'),
            ],
            'provisioning.completed' => [
                'title' => 'Welcome — your accounts are ready',
                'body' => 'Your email and system accounts have been provisioned. Check your inbox for credentials.',
            ],
            'employee.welcome' => [
                'title' => 'Welcome to the company',
                'body' => sprintf('Hi %s, your onboarding has started.', $context['name'] ?? 'colleague'),
            ],
            default => [
                'title' => $context['title'] ?? 'Notification',
                'body' => $context['body'] ?? '',
            ],
        };
    }
}
