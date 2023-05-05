<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Implemented by entities that are associated with an account.
 */
interface BelongsToAccount
{
    public function getAccountId(): int;

    public function belongsToAccount(Account|int $accountId): bool;

    public function belongsToSameAccount(self|AccountMember $entity): bool;
}
