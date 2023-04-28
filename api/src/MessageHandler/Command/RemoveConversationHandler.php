<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Permission;
use App\Message\Command\RemoveConversation;
use App\Repository\ConversationCommentRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveConversationHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private ConversationRepository $conversationRepository,
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(RemoveConversation $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $conversation = $this->conversationRepository->getById($command->conversationId);

        $this->denyAccessUnlessGranted($user, Permission::REMOVE_CONVERSATION, $conversation);
        $this->conversationRepository->remove($conversation);
        $this->conversationCommentRepository->removeByConversationId($command->conversationId);
    }
}
