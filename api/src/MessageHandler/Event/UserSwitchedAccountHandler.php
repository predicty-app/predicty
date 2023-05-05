<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\UserSwitchedAccount;
use App\Repository\UserWithAccountRepository;
use App\Service\Security\UserRoleUpdater;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserSwitchedAccountHandler
{
    public function __construct(
        private UserWithAccountRepository $userWithAccountRepository,
        private UserRoleUpdater $userRoleUpdater
    ) {
    }

    public function __invoke(UserSwitchedAccount $event): void
    {
        $user = $this->userWithAccountRepository->get($event->userId, $event->accountId);
        $this->userRoleUpdater->update($user);
    }
}
