<?php

declare(strict_types=1);

namespace App\Message\Event;

class ImportWithdrew
{
    public int $userId;
    public int $importId;

    public function __construct(int $userId, int $importId)
    {
        $this->userId = $userId;
        $this->importId = $importId;
    }
}
