<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\ConversationComment;
use App\Entity\Permission;
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

        // we do not support above permissions without a comment
        if ($subject === null) {
            return false;
        }

        // we support removing comments and editing them, but only if the user owns them
        return $subject->isOwnedBy($user);
    }
}
