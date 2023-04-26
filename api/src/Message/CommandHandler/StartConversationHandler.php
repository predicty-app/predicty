<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\Color;
use App\Entity\Conversation;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\AddConversationComment;
use App\Message\Command\StartConversation;
use App\Repository\ConversationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StartConversationHandler
{
    use DispatchCommandTrait;

    public function __construct(private ConversationRepository $conversationRepository)
    {
    }

    // @todo implement locking so that there will are no conflicts when creating new conversations
    public function __invoke(StartConversation $command): void
    {
        $conversation = $this->conversationRepository->findByUserIdAndDate($command->userId, $command->date);

        if ($conversation === null) {
            $conversation = new Conversation($command->userId, $command->date, Color::fromString($command->color));
            $this->conversationRepository->save($conversation);
        }

        if ($command->comment !== '') {
            $this->dispatch(new AddConversationComment($conversation->getId(), $command->userId, $command->comment));
        }
    }
}
