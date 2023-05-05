<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Account;
use App\Validator as AssertCustom;

class SwitchAccount
{
    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\EntityExists(entity: Account::class, message: 'Account does not exist')]
    public int $accountId;

    public function __construct(int $userId, int $accountId)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
