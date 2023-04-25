<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\RemoveConversation;
use App\Repository\ConversationCommentRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveConversationHandler
{
    public function __construct(
        private ConversationRepository $conversationRepository,
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    // @todo proper permission checking and error handling
    public function __invoke(RemoveConversation $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $conversation = $this->conversationRepository->getById($command->conversationId);

        if ($conversation->isOwnedBy($user)) {
            $this->conversationRepository->remove($conversation);
            $this->conversationCommentRepository->removeByConversationId($command->conversationId);
        }
    }
}
