<?php

declare(strict_types=1);

namespace Modules\Audit\Observers;

use App\Enums\AuditEvent;
use Illuminate\Database\Eloquent\Model;
use Modules\Audit\Services\AuditService;

class AuditObserver
{
    /**
     * In-memory snapshots keyed by model identity (class + key).
     * We avoid setAttribute() because it would persist as a column on save().
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $snapshots = [];

    public function __construct(private readonly AuditService $auditService)
    {
    }

    public function created(Model $model): void
    {
        $this->auditService->log(
            auditable: $model,
            event: AuditEvent::CREATED,
            oldValues: null,
            newValues: $this->maskSensitive($model, $this->filter($model, $model->getAttributes())),
        );
    }

    public function updating(Model $model): void
    {
        self::$snapshots[$this->key($model)] = $model->getOriginal();
    }

    public function updated(Model $model): void
    {
        $key = $this->key($model);
        $before = self::$snapshots[$key] ?? [];
        unset(self::$snapshots[$key]);

        $after = $model->getChanges();
        $filteredBefore = $this->filter($model, array_intersect_key($before, $after));
        $filteredAfter = $this->filter($model, $after);
        $changed = array_keys($filteredAfter);

        if ($changed === []) {
            return;
        }

        $payrollSensitive = (bool) array_intersect($changed, $this->getSensitiveFields($model));

        $this->auditService->log(
            auditable: $model,
            event: AuditEvent::UPDATED,
            oldValues: $this->maskSensitive($model, $filteredBefore),
            newValues: $this->maskSensitive($model, $filteredAfter),
            changedFields: $changed,
            payrollSensitive: $payrollSensitive,
        );
    }

    public function deleted(Model $model): void
    {
        $this->auditService->log(
            auditable: $model,
            event: AuditEvent::DELETED,
            oldValues: $this->maskSensitive($model, $this->filter($model, $model->getAttributes())),
            newValues: null,
        );
    }

    public function restored(Model $model): void
    {
        $this->auditService->log(
            auditable: $model,
            event: AuditEvent::RESTORED,
            newValues: $this->filter($model, $model->getAttributes()),
        );
    }

    private function key(Model $model): string
    {
        return $model::class.':'.($model->getKey() ?? spl_object_id($model));
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private function filter(Model $model, array $values): array
    {
        $fields = method_exists($model, 'getAuditableFields') ? $model->getAuditableFields() : ['*'];

        if ($fields === ['*']) {
            return array_diff_key($values, array_flip([
                'updated_at', 'created_at', 'remember_token', 'password',
            ]));
        }

        return array_intersect_key($values, array_flip($fields));
    }

    /**
     * @return array<int, string>
     */
    private function getSensitiveFields(Model $model): array
    {
        return method_exists($model, 'getSensitiveFields') ? $model->getSensitiveFields() : [];
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private function maskSensitive(Model $model, array $values): array
    {
        $placeholder = config('audit.redact_placeholder', '[REDACTED]');

        foreach ($this->getSensitiveFields($model) as $field) {
            if (array_key_exists($field, $values)) {
                $values[$field] = $placeholder;
            }
        }

        return $values;
    }
}
