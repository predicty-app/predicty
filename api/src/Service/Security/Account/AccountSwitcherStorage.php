<?php

declare(strict_types=1);

namespace App\Service\Security\Account;

/**
 * @internal
 */
interface AccountSwitcherStorage
{
    public function set(int $userId, int $accountId): void;

    public function get(int $userId): ?int;
}
