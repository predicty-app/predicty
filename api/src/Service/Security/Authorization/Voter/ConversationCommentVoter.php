<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\ConversationComment;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;

/**
 * @extends Voter<ConversationComment>
 */
class ConversationCommentVoter extends Voter
{
    protected function getSupportedType(): string
    {
        return ConversationComment::class;
    }

    protected function getSupportedPermissions(): array
    {
        return [
            Permission::REMOVE_CONVERSATION_COMMENT,
            Permission::EDIT_CONVERSATION_COMMENT,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        // we do not support manipulating comments without a user
        if ($user === null) {
            return false;
        }

        // only account owners and comment owners can change comments
        return $this->hasRole(Role::ROLE_ACCOUNT_OWNER) || $this->isAnOwnerOf($subject);
    }
}
