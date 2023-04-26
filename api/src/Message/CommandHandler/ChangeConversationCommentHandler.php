<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ChangeConversationComment;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangeConversationCommentHandler
{
    public function __construct(
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    // @todo proper permission checking and error handling
    public function __invoke(ChangeConversationComment $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $comment = $this->conversationCommentRepository->getById($command->commentId);

        if ($comment->isOwnedBy($user)) {
            $comment->changeComment($command->comment);
            $this->conversationCommentRepository->save($comment);
        }
    }
}
