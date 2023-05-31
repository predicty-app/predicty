<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class SwitchAccount
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $userId, Ulid $accountId)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
