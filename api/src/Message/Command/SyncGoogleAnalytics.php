<?php

declare(strict_types=1);

namespace App\Message\Command;

class SyncGoogleAnalytics
{
    public function __construct(public int $userId)
    {
    }
}
