<?php

declare(strict_types=1);

namespace App\Service\Security\Account;

use App\Entity\Account;
use App\Entity\AccountMember;
use App\Entity\UserWithId;
use App\Repository\AccountRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AccountSwitcher implements AccountContextProvider
{
    public function __construct(private AccountRepository $accountRepository, private AccountSwitcherStorage $storage)
    {
    }

    public function switchAccount(UserWithId&AccountMember $user, int|Account $account): void
    {
        if ($account instanceof Account) {
            $account = $account->getId();
        }

        if (!$user->isMemberOf($account)) {
            $exception = new CustomUserMessageAuthenticationException(sprintf('Unable to switch accounts - user does not belong to account "%s"', $account));
            $exception->setSafeMessage('Unable to switch accounts');

            throw $exception;
        }

        $this->storage->set($user->getId(), $account);
    }

    public function getCurrentlySelectedAccount(UserWithId&AccountMember $user): ?Account
    {
        $accountId = $this->storage->get($user->getId());
        $account = null;

        if ($accountId !== null) {
            $account = $this->accountRepository->findById($accountId);
        }

        // fallback to default account
        if ($account === null && isset($user->getAccountsIds()[0])) {
            $account = $this->accountRepository->findById($user->getAccountsIds()[0]);
        }

        return $account;
    }
}
