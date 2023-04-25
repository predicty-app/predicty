<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\RemoveConversationComment;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveConversationCommentHandler
{
    public function __construct(
        private ConversationCommentRepository $conversationCommentRepository,
        private UserRepository $userRepository,
    ) {
    }

    // @todo proper permission checking and error handling
    public function __invoke(RemoveConversationComment $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $comment = $this->conversationCommentRepository->getById($command->commentId);

        if ($comment->isOwnedBy($user)) {
            $this->conversationCommentRepository->remove($comment);
        }
    }
}
