<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\Permission;
use App\Message\Command\ChangeConversationComment;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangeConversationCommentHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(ChangeConversationComment $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $comment = $this->conversationCommentRepository->getById($command->commentId);

        $this->denyAccessUnlessGranted($user, Permission::EDIT_CONVERSATION_COMMENT, $comment);

        $comment->changeComment($command->comment);
        $this->conversationCommentRepository->save($comment);
    }
}
