<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\AccountAwareUser;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<User>
 */
class UserVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return User::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::MANAGE_ACCOUNT,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        if ($user instanceof AccountAwareUser && $subject instanceof User) {
            return
                // user cannot manage himself
                $user->getId() !== $subject->getId()
                // affected user must be the member of the same account
                && $subject->isMemberOf($user->getAccount())
                // acting user must be an account owner
                && $this->hasRole(Role::ROLE_ACCOUNT_OWNER, $user->getAccount());
        }

        return false;
    }
}
