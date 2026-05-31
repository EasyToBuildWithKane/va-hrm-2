<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Audit\Services\AuditArchiveService;

class ArchiveAuditLogs extends Command
{
    protected $signature = 'audit:archive-old';

    protected $description = 'Move audit logs older than retention threshold to the archive table';

    public function handle(AuditArchiveService $service): int
    {
        $moved = $service->archiveOldRecords();
        $this->info("Archived {$moved} audit log(s).");

        return self::SUCCESS;
    }
}
