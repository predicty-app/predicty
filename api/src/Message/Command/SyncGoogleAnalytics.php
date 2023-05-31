<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class SyncGoogleAnalytics
{
    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(public Ulid $userId, Ulid $accountId)
    {
        $this->accountId = $accountId;
    }
}
