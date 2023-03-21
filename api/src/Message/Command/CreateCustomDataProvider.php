<?php

declare(strict_types=1);

namespace App\Message\Command;

class CreateCustomDataProvider
{
    public readonly string $name;
    public readonly int $userId;

    public function __construct(int $userId, string $name)
    {
        $this->name = $name;
        $this->userId = $userId;
    }
}
