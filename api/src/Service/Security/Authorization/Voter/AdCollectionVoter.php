<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\AdCollection;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<AdCollection>
 */
class AdCollectionVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return AdCollection::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::ADD_AD_TO_AD_COLLECTION,
            Permission::REMOVE_AD_FROM_AD_COLLECTION,
        ];
    }

    /**
     * @param AdCollection $subject
     */
    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        // user must be present
        if ($user === null) {
            return false;
        }

        return match ($permission) {
            Permission::ADD_AD_TO_AD_COLLECTION, Permission::REMOVE_AD_FROM_AD_COLLECTION => $this->hasRole(Role::ROLE_ACCOUNT_MEMBER, $subject->getAccountId()),
            default => false,
        };
    }
}
