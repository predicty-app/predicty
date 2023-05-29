<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Permission;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ChangeAccountRole;
use App\Message\Event\UserRoleChanged;
use App\Repository\UserRepository;
use App\Repository\UserWithAccountRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangeAccountRoleHandler
{
    use AuthorizationCheckerTrait;
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private UserWithAccountRepository $userWithAccountRepository,
    ) {
    }

    public function __invoke(ChangeAccountRole $message): void
    {
        $actingUser = $this->userWithAccountRepository->get($message->actingUserId, $message->accountId);
        $affectedUser = $this->userRepository->getById($message->affectedUserId);

        $this->denyAccessUnlessGranted($actingUser, Permission::MANAGE_ACCOUNT, $affectedUser);
        $affectedUser->setAccountRole($message->accountId, $message->role);
        $this->userRepository->save($affectedUser);
        $this->emit(new UserRoleChanged($actingUser->getId(), $affectedUser->getId(), $message->accountId, $message->role));
    }
}
