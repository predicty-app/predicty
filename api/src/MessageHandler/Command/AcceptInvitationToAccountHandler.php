<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\User;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\AcceptInvitationToAccount;
use App\Message\Command\Register;
use App\Message\Event\InvitationToAccountAccepted;
use App\Repository\AccountInvitationRepository;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use LogicException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AcceptInvitationToAccountHandler
{
    use AuthorizationCheckerTrait;
    use DispatchCommandTrait;
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
        private AccountInvitationRepository $accountInvitationRepository,
    ) {
    }

    public function __invoke(AcceptInvitationToAccount $message): void
    {
        $invitation = $this->accountInvitationRepository->getById($message->invitationId);
        $account = $this->accountRepository->getById($invitation->getAccountId());
        $user = $this->getOrCreateUserAccount($invitation->getEmail(), $message->acceptedTermsOfServiceVersion, $message->hasAgreedToNewsletter);

        if (strcasecmp($user->getEmail(), $invitation->getEmail()) !== 0) {
            throw new LogicException('User email does not match invitation email');
        }

        if ($user->isMemberOf($account)) {
            // do nothing if user is already a member of the account
            return;
        }

        $user->setAsAccountMember($account);
        $this->userRepository->save($user);

        $this->emit(new InvitationToAccountAccepted($invitation->getId(), $invitation->getUserId(), $user->getId(), $account->getId()));
        $this->accountInvitationRepository->remove($invitation);
    }

    private function getOrCreateUserAccount(string $email, int $acceptedTermsOfServiceVersion, bool $hasAgreedToNewsletter): User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            $this->dispatch(new Register($email, $acceptedTermsOfServiceVersion, $hasAgreedToNewsletter));
            $user = $this->userRepository->getByEmail($email);
        }

        return $user;
    }
}
