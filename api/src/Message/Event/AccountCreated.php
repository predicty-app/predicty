<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;

class AccountCreated implements Event
{
    public function __construct(public int $accountId, public int $userId)
    {
    }
}
