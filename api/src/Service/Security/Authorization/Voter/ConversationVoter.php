<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Conversation;
use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        $supported = [
            Permission::START_CONVERSATION,
            Permission::REMOVE_CONVERSATION,
            Permission::ADD_CONVERSATION_COMMENT,
        ];

        return in_array($attribute, $supported, true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var null|Conversation $conversation */
        $conversation = $subject;
        $user = $token->getUser();

        if ($user instanceof User) {
            if ($conversation === null) {
                if ($attribute === Permission::START_CONVERSATION) {
                    return true;
                }

                return false;
            }

            return match ($attribute) {
                Permission::REMOVE_CONVERSATION, Permission::REMOVE_CONVERSATION_COMMENT, Permission::ADD_CONVERSATION_COMMENT => $conversation->isOwnedBy($user),
                Permission::START_CONVERSATION => true,
                default => false,
            };
        }

        return false;
    }
}
