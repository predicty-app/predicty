<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Ulid;

/**
 * This interface covers all readonly account related methods of the user entity.
 */
interface AccountMember
{
    /**
     * Get all account ids the user is a member of.
     *
     * @return array<Ulid>
     */
    public function getAccountsIds(): array;

    public function isMemberOf(Account|Ulid $account): bool;

    /**
     * Get all roles the user has in the given account.
     *
     * @return array<string>
     */
    public function getRolesForAccount(Account|Ulid $account): array;
}
