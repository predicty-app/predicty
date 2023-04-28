<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Permission;
use App\Message\Command\RemoveConversationComment;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveConversationCommentHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(RemoveConversationComment $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $comment = $this->conversationCommentRepository->getById($command->commentId);

        $this->denyAccessUnlessGranted($user, Permission::REMOVE_CONVERSATION_COMMENT, $comment);
        $this->conversationCommentRepository->remove($comment);
    }
}
