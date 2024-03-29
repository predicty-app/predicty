<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Account;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<Account>
 */
class AccountVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return Account::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::START_CONVERSATION,
            Permission::CREATE_ACCOUNT,
            Permission::MANAGE_ACCOUNT,
            Permission::SWITCH_ACCOUNT,
            Permission::CREATE_AD_COLLECTION,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($subject === null) {
            return match ($permission) {
                Permission::CREATE_ACCOUNT => true,
                default => $this->hasRole(Role::ROLE_ACCOUNT_OWNER),
            };
        }

        return match ($permission) {
            Permission::START_CONVERSATION => $this->hasRole(Role::ROLE_ACCOUNT_MEMBER, $subject),
            Permission::SWITCH_ACCOUNT => $user->isMemberOf($subject),
            default => $this->hasRole(Role::ROLE_ACCOUNT_OWNER, $subject),
        };
    }

    protected function isNullSubjectAllowed(): bool
    {
        return true;
    }
}
