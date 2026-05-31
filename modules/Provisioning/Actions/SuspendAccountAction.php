<?php

declare(strict_types=1);

namespace Modules\Provisioning\Actions;

use Modules\Provisioning\Engine\AccessRevoker;
use Modules\Provisioning\Events\AccountSuspended;
use Modules\Provisioning\Models\AccountProvision;

final class SuspendAccountAction
{
    public function __construct(private readonly AccessRevoker $revoker)
    {
    }

    public function __invoke(AccountProvision $account): AccountProvision
    {
        $account = $this->revoker->suspend($account);

        event(new AccountSuspended($account));

        return $account;
    }
}
