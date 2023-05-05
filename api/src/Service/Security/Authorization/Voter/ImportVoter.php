<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Import;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<Import>
 */
class ImportVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return Import::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::WITHDRAW_IMPORT,
            Permission::DOWNLOAD_IMPORTED_FILE,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        // Both user and subject are required
        if ($user === null || $subject === null) {
            return false;
        }

        return match ($permission) {
            Permission::WITHDRAW_IMPORT => $this->hasRole(Role::ROLE_ACCOUNT_MEMBER, $subject->getAccountId()),
            Permission::DOWNLOAD_IMPORTED_FILE => $this->hasRole(Role::ROLE_ACCOUNT_OWNER, $subject->getAccountId()),
            default => false,
        };
    }
}
