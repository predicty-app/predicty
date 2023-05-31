<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Uid\Ulid;

class CompleteOnboarding
{
    public readonly Ulid $userId;

    public function __construct(Ulid $userId)
    {
        $this->userId = $userId;
    }
}
