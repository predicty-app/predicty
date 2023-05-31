<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Permission;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\SwitchAccount;
use App\Message\Event\UserSwitchedAccount;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Security\Account\AccountSwitcher;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SwitchAccountHandler
{
    use AuthorizationCheckerTrait;
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
        private AccountSwitcher $accountSwitcher
    ) {
    }

    public function __invoke(SwitchAccount $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $account = $this->accountRepository->getById($message->accountId);

        $this->denyAccessUnlessGranted($user, Permission::SWITCH_ACCOUNT, $account);
        $this->accountSwitcher->switchAccount($user, $account);
        $this->emit(new UserSwitchedAccount($user->getId(), $account->getId()));
    }
}
