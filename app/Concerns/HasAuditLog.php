<?php

declare(strict_types=1);

namespace App\Concerns;

use Modules\Audit\Observers\AuditObserver;

trait HasAuditLog
{
    public static function bootHasAuditLog(): void
    {
        static::observe(AuditObserver::class);
    }

    /**
     * Fields tracked in audit log. ['*'] means all attributes.
     *
     * @return array<int, string>
     */
    public function getAuditableFields(): array
    {
        return property_exists($this, 'auditableFields') ? $this->auditableFields : ['*'];
    }

    /**
     * Fields masked in audit log (stored as [REDACTED]).
     *
     * @return array<int, string>
     */
    public function getSensitiveFields(): array
    {
        return property_exists($this, 'sensitiveFields') ? $this->sensitiveFields : [];
    }
}
