<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;

class SyncGoogleAnalytics
{
    #[AssertCustom\AccountExists]
    public int $accountId;

    public function __construct(public int $userId, int $accountId)
    {
        $this->accountId = $accountId;
    }
}
