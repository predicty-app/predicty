<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Account;
use App\Entity\Permission;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\CreateAccount;
use App\Message\Event\AccountCreated;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class CreateAccountHandler
{
    use AuthorizationCheckerTrait;
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
    ) {
    }

    public function __invoke(CreateAccount $message): Account
    {
        $user = $this->userRepository->getById($message->userId);
        $this->denyAccessUnlessGranted($user, Permission::CREATE_ACCOUNT);

        $account = new Account(new Ulid(), $user->getId(), $message->name);
        $this->accountRepository->save($account);

        $user->setAsAccountOwner($account->getId());
        $this->userRepository->save($user);

        $this->emit(new AccountCreated($account->getId(), $user->getId()));

        return $account;
    }
}
