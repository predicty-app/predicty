<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Conversation;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<Conversation>
 */
class ConversationVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return Conversation::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::REMOVE_CONVERSATION,
            Permission::ADD_CONVERSATION_COMMENT,
        ];
    }

    /**
     * @param Conversation $subject
     */
    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        // we do not support manipulating conversations without a user
        if ($user === null) {
            return false;
        }

        return match ($permission) {
            Permission::REMOVE_CONVERSATION => $this->isAnOwnerOf($subject) || $this->hasRole(Role::ROLE_ACCOUNT_OWNER, $subject->getAccountId()),
            Permission::ADD_CONVERSATION_COMMENT => $this->hasRole(Role::ROLE_ACCOUNT_MEMBER, $subject->getAccountId()),
            default => false,
        };
    }
}
