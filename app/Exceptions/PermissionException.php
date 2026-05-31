<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class PermissionException extends RuntimeException
{
    public function __construct(
        string $message = 'Insufficient permissions',
        public readonly ?string $requiredPermission = null,
    ) {
        parent::__construct($message);
    }
}
