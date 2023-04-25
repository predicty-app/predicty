<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\ConversationComment;
use App\Message\Command\AddConversationComment;
use App\Repository\ConversationCommentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddConversationCommentHandler
{
    public function __construct(private ConversationCommentRepository $conversationCommentRepository)
    {
    }

    public function __invoke(AddConversationComment $command): void
    {
        $comment = new ConversationComment($command->conversationId, $command->userId, $command->comment);
        $this->conversationCommentRepository->save($comment);
    }
}
