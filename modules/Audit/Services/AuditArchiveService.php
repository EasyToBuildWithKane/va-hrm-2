<?php

declare(strict_types=1);

namespace Modules\Audit\Services;

use Illuminate\Support\Facades\DB;

class AuditArchiveService
{
    public function archiveOldRecords(): int
    {
        $retention = (int) config('audit.retention.hot_table_years', 2);
        $payroll = (int) config('audit.retention.payroll_sensitive_years', 7);
        $cutoff = now()->subYears($retention);
        $payrollCutoff = now()->subYears($payroll);

        return DB::transaction(function () use ($cutoff, $payrollCutoff): int {
            $movable = DB::table('audit_logs')
                ->where(function ($query) use ($cutoff, $payrollCutoff): void {
                    $query->where(function ($q) use ($cutoff): void {
                        $q->where('payroll_sensitive', false)->where('created_at', '<', $cutoff);
                    })->orWhere(function ($q) use ($payrollCutoff): void {
                        $q->where('payroll_sensitive', true)->where('created_at', '<', $payrollCutoff);
                    });
                })
                ->get();

            if ($movable->isEmpty()) {
                return 0;
            }

            DB::table('audit_logs_archive')->insert($movable->map(fn ($row) => (array) $row)->all());
            DB::table('audit_logs')->whereIn('id', $movable->pluck('id'))->delete();

            return $movable->count();
        });
    }
}
