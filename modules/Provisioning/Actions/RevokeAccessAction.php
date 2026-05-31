<?php

declare(strict_types=1);

namespace Modules\Provisioning\Actions;

use Modules\Provisioning\Engine\AccessRevoker;
use Modules\Provisioning\Models\AccountProvision;

final class RevokeAccessAction
{
    public function __construct(private readonly AccessRevoker $revoker)
    {
    }

    public function __invoke(AccountProvision $account): AccountProvision
    {
        return $this->revoker->revoke($account);
    }
}
