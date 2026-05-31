<?php

declare(strict_types=1);

namespace Modules\Audit\Services;

class DiffService
{
    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return array<string, array{before: mixed, after: mixed, type: string}>
     */
    public static function compute(array $before, array $after): array
    {
        $diff = [];
        $keys = array_unique(array_merge(array_keys($before), array_keys($after)));

        foreach ($keys as $field) {
            $oldValue = $before[$field] ?? null;
            $newValue = $after[$field] ?? null;

            if ($oldValue !== $newValue) {
                $diff[$field] = [
                    'before' => $oldValue,
                    'after' => $newValue,
                    'type' => self::classify($oldValue, $newValue),
                ];
            }
        }

        return $diff;
    }

    private static function classify(mixed $before, mixed $after): string
    {
        if ($before === null && $after !== null) {
            return 'added';
        }
        if ($before !== null && $after === null) {
            return 'removed';
        }

        return 'changed';
    }
}
