<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Color;
use App\Entity\Conversation;
use App\Entity\Permission;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\AddConversationComment;
use App\Message\Command\StartConversation;
use App\Repository\ConversationRepository;
use App\Repository\UserWithAccountRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class StartConversationHandler
{
    use AuthorizationCheckerTrait;
    use DispatchCommandTrait;

    public function __construct(
        private ConversationRepository $conversationRepository,
        private UserWithAccountRepository $userWithAccountRepository
    ) {
    }

    // @todo implement locking so that there will are no conflicts when creating new conversations
    // @todo transactions
    public function __invoke(StartConversation $command): void
    {
        $user = $this->userWithAccountRepository->get($command->userId, $command->accountId);
        $conversation = $this->conversationRepository->findByAccountIdAndDate($command->accountId, $command->date);

        if ($conversation === null) {
            $this->denyAccessUnlessGranted($user, Permission::START_CONVERSATION, $user->getAccount());
            $conversation = new Conversation(new Ulid(), $command->userId, $command->accountId, $command->date, Color::fromString($command->color));
            $this->conversationRepository->save($conversation);
        }

        if ($command->comment !== '') {
            $this->dispatch(new AddConversationComment($conversation->getId(), $command->userId, $command->accountId, $command->comment));
        }
    }
}
