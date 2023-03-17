<?php

declare(strict_types=1);

namespace App\Message\Command;

class CompleteOnboarding
{
    public readonly int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
