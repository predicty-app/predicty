<?php

declare(strict_types=1);

namespace App\Service\Security\Account;

use Symfony\Component\Uid\Ulid;

/**
 * @internal
 */
interface AccountSwitcherStorage
{
    public function set(Ulid $userId, Ulid $accountId): void;

    public function get(Ulid $userId): ?Ulid;
}
