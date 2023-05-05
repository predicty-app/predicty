<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Entity\DataProvider;
use App\Message\Event;

class AccountConnected implements Event
{
    public function __construct(public int $accountId, public int $connectedAccountId, public DataProvider $dataProvider)
    {
    }
}
