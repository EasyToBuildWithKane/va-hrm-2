<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class WorkflowException extends RuntimeException
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        string $message,
        public readonly string $errorCode = 'WORKFLOW_ERROR',
        public readonly int $httpStatus = 422,
        public readonly array $context = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function notAuthorized(int $workflowId, int $stepId, ?string $requiredRole = null): self
    {
        return new self(
            message: 'Approval step not authorized',
            errorCode: 'WORKFLOW_PERMISSION_DENIED',
            httpStatus: 403,
            context: [
                'workflow_id' => $workflowId,
                'step_id' => $stepId,
                'required_role' => $requiredRole,
            ],
        );
    }

    public static function invalidTransition(string $from, string $to): self
    {
        return new self(
            message: "Invalid workflow transition from {$from} to {$to}",
            errorCode: 'WORKFLOW_INVALID_TRANSITION',
            httpStatus: 409,
        );
    }

    public static function configurationMissing(string $workflowType): self
    {
        return new self(
            message: "No active workflow configuration for: {$workflowType}",
            errorCode: 'WORKFLOW_CONFIG_MISSING',
            httpStatus: 422,
        );
    }
}
