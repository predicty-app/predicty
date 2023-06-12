<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Entity\DataProvider;
use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class AccountConnected implements Event
{
    public function __construct(public Ulid $accountId, public Ulid $connectedAccountId, public DataProvider $dataProvider)
    {
    }
}
