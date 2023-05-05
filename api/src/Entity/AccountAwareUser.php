<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Implemented by user entities that are aware of the account context they are being accessed in.
 */
interface AccountAwareUser extends AccountMember
{
    public function getAccountId(): int;

    public function getAccount(): Account;

    /**
     * @see AccountAwareUser::getRoles()
     */
    public function getAccountRoles(): array;

    public function hasAccountRole(string $role): bool;

    /**
     * Unlike symfony default logic, this method will return roles assigned to the user in the current account context.
     * Also, any system roles assigned directly to the user will be included.
     */
    public function getRoles(): array;
}
