<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\InvitationToAccountAccepted;
use App\Notification\InvitationToAccountAcceptedForAccountManager;
use App\Notification\InvitationToAccountAcceptedForNewMember;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class InvitationToAccountAcceptedHandler
{
    public function __construct(
        private NotifierInterface $notifier,
        private AccountRepository $accountRepository,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(InvitationToAccountAccepted $event): void
    {
        $account = $this->accountRepository->getById($event->accountId);
        $invitingUser = $this->userRepository->getById($event->invitingUserId);
        $invitedUser = $this->userRepository->getById($event->invitedUserId);

        $this->notifier->send(new InvitationToAccountAcceptedForNewMember($account->getName()), $invitedUser);
        $this->notifier->send(new InvitationToAccountAcceptedForAccountManager($invitedUser->getEmail(), $account->getName()), $invitingUser);
    }
}
