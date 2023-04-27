<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\ConversationComment;
use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationCommentVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        $supported = [
            Permission::REMOVE_CONVERSATION_COMMENT,
            Permission::EDIT_CONVERSATION_COMMENT,
        ];

        return in_array($attribute, $supported, true) && $subject instanceof ConversationComment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var ConversationComment $conversationComment */
        $conversationComment = $subject;
        $user = $token->getUser();

        if ($user instanceof User) {
            return match ($attribute) {
                Permission::REMOVE_CONVERSATION_COMMENT, Permission::EDIT_CONVERSATION_COMMENT => $conversationComment->isOwnedBy($user),
                default => false,
            };
        }

        return false;
    }
}
