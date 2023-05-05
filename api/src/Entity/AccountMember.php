<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * This interface covers all readonly account related methods of the user entity.
 */
interface AccountMember
{
    /**
     * Get all account ids the user is a member of.
     *
     * @return array<int>
     */
    public function getAccountsIds(): array;

    public function isMemberOf(Account|int $account): bool;

    /**
     * Get all roles the user has in the given account.
     *
     * @return array<string>
     */
    public function getRolesForAccount(Account|int $account): array;
}
