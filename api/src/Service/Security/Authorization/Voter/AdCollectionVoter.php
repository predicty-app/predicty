<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\AdCollection;
use App\Entity\Permission;
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
            Permission::CREATE_AD_COLLECTION,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($subject === null) {
            return $permission === Permission::CREATE_AD_COLLECTION;
        }

        return $subject->isOwnedBy($user);
    }
}
