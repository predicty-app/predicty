<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class SyncGoogleAds
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public Ulid $connectedAccountId;

    public function __construct(Ulid $userId, Ulid $accountId, Ulid $connectedAccountId)
    {
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->connectedAccountId = $connectedAccountId;
    }
}
