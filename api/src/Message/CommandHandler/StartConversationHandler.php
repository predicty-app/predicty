<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\Color;
use App\Entity\Conversation;
use App\Entity\Permission;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\AddConversationComment;
use App\Message\Command\StartConversation;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StartConversationHandler
{
    use AuthorizationCheckerTrait;
    use DispatchCommandTrait;

    public function __construct(
        private ConversationRepository $conversationRepository,
        private UserRepository $userRepository,
    ) {
    }

    // @todo implement locking so that there will are no conflicts when creating new conversations
    public function __invoke(StartConversation $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $conversation = $this->conversationRepository->findByUserIdAndDate($command->userId, $command->date);

        if ($conversation === null) {
            $this->denyAccessUnlessGranted($user, Permission::START_CONVERSATION);
            $conversation = new Conversation($command->userId, $command->date, Color::fromString($command->color));
            $this->conversationRepository->save($conversation);
        }

        if ($command->comment !== '') {
            $this->denyAccessUnlessGranted($user, Permission::ADD_CONVERSATION_COMMENT, $conversation);
            $this->dispatch(new AddConversationComment($conversation->getId(), $command->userId, $command->comment));
        }
    }
}
