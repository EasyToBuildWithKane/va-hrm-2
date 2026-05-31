<?php

declare(strict_types=1);

namespace App\Contracts;

interface Auditable
{
    /**
     * @return array<int, string>
     */
    public function getAuditableFields(): array;

    /**
     * @return array<int, string>
     */
    public function getSensitiveFields(): array;
}
