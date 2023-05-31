<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class AccountCreated implements Event
{
    public function __construct(public Ulid $accountId, public Ulid $userId)
    {
    }
}
