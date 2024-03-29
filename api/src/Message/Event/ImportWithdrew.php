<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class ImportWithdrew implements Event
{
    public Ulid $userId;
    public Ulid $importId;

    public function __construct(Ulid $userId, Ulid $importId)
    {
        $this->userId = $userId;
        $this->importId = $importId;
    }
}
