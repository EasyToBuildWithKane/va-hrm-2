<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Organization\Services\OrganizationGraphService;

class SyncOrganizationGraph extends Command
{
    protected $signature = 'organization:sync-graph';

    protected $description = 'Rebuild organization graph nodes and relationships from source tables';

    public function handle(OrganizationGraphService $service): int
    {
        $service->syncGraph();
        $this->info('Organization graph synced.');

        return self::SUCCESS;
    }
}
