<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\AccountInvitation;
use App\Entity\Permission;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\InviteToAccount;
use App\Message\Event\InvitationToAccountSent;
use App\Notification\AccountInvitationIssuedNotification;
use App\Repository\AccountInvitationRepository;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Clock\Clock;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class InviteToAccountHandler
{
    use AuthorizationCheckerTrait;
    use EmitEventTrait;

    private const ACCOUNT_INVITATION_VALIDITY = '+1 day';

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
        private AccountInvitationRepository $accountInvitationRepository,
        private UrlGeneratorInterface $urlGenerator,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(InviteToAccount $message): void
    {
        $invitingUser = $this->userRepository->getById($message->invitingUserId);
        $account = $this->accountRepository->getById($message->accountId);
        $this->denyAccessUnlessGranted($invitingUser, Permission::MANAGE_ACCOUNT, $account);

        $validTo = Clock::now()->modify(self::ACCOUNT_INVITATION_VALIDITY);
        $invitation = new AccountInvitation(
            id: new Ulid(),
            userId: $message->invitingUserId,
            accountId: $message->accountId,
            email: $message->email,
            validTo: $validTo
        );

        $url = $this->urlGenerator->generate('app_accept_invitation', ['id' => $invitation->getId()->toRfc4122()], UrlGeneratorInterface::ABSOLUTE_URL);

        // this is the key action here, it is needed as part of this command,
        // therefore we are not extracting it into an event handler (it is not a side effect)
        $this->accountInvitationRepository->save($invitation);
        $this->notifier->send(new AccountInvitationIssuedNotification($account->getName(), $url), new Recipient($message->email));

        $this->emit(new InvitationToAccountSent($invitation->getId(), $message->invitingUserId, $message->accountId, $message->email));
    }
}
