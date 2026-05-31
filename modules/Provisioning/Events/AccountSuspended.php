<?php

declare(strict_types=1);

namespace Modules\Provisioning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Provisioning\Models\AccountProvision;

class AccountSuspended
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly AccountProvision $account)
    {
    }
}
