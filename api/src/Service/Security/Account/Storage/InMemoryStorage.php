<?php

declare(strict_types=1);

namespace App\Service\Security\Account\Storage;

use App\Service\Security\Account\AccountSwitcherStorage;

class InMemoryStorage implements AccountSwitcherStorage
{
    public array $mockData = [];

    public function set(int $userId, int $accountId): void
    {
        $this->mockData[$userId] = $accountId;
    }

    public function get(int $userId): ?int
    {
        return $this->mockData[$userId] ?? null;
    }
}
