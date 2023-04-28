<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\ConversationComment;
use App\Entity\Permission;
use App\Message\Command\AddConversationComment;
use App\Repository\ConversationCommentRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddConversationCommentHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private ConversationRepository $conversationRepository,
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(AddConversationComment $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $conversation = $this->conversationRepository->getById($command->conversationId);

        $this->denyAccessUnlessGranted($user, Permission::ADD_CONVERSATION_COMMENT, $conversation);

        $comment = new ConversationComment($command->conversationId, $command->userId, $command->comment);
        $this->conversationCommentRepository->save($comment);
    }
}
