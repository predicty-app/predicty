<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Ulid;

/**
 * Implemented by entities that are associated with an account.
 * For user entities, see {@see AccountAwareUser}.
 */
interface AccountOwnable
{
    public function getAccountId(): Ulid;

    public function belongsToAccount(Account|Ulid $accountId): bool;

    public function belongsToSameAccount(self|AccountMember $entity): bool;
}
