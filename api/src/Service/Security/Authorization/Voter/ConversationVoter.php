<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Conversation;
use App\Entity\Permission;
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
            Permission::START_CONVERSATION,
            Permission::REMOVE_CONVERSATION,
            Permission::ADD_CONVERSATION_COMMENT,
        ];
    }

    protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool
    {
        // we do not support manipulating conversations without a user
        if ($user === null) {
            return false;
        }

        // we support starting conversations without a subject, any user is allowed
        if ($subject === null) {
            return $permission === Permission::START_CONVERSATION;
        }

        // we support removing conversations and adding comments to them, but only if the user owns them
        return $subject->isOwnedBy($user);
    }
}
