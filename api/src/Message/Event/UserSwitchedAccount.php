<?php

declare(strict_types=1);

namespace App\Message\Event;

class UserSwitchedAccount
{
    public function __construct(public int $userId, public int $accountId)
    {
    }
}