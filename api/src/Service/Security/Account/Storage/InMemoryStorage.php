<?php

declare(strict_types=1);

namespace App\Service\Security\Account\Storage;

use App\Service\Security\Account\AccountSwitcherStorage;
use Symfony\Component\Uid\Ulid;

class InMemoryStorage implements AccountSwitcherStorage
{
    public array $mockData = [];

    public function set(Ulid $userId, Ulid $accountId): void
    {
        $this->mockData[(string) $userId] = $accountId;
    }

    public function get(Ulid $userId): ?Ulid
    {
        return $this->mockData[(string) $userId] ?? null;
    }
}
