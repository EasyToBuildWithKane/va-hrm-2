<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Contribution\Engine\ContributionEngine;

class SyncContributionScores extends Command
{
    protected $signature = 'contribution:sync-scores';

    protected $description = 'Recompute every employee contribution score and rebuild rankings';

    public function handle(ContributionEngine $engine): int
    {
        $engine->rebuildAllScores();
        $engine->rebuildRankings();

        $this->info('Contribution scores synced.');

        return self::SUCCESS;
    }
}
