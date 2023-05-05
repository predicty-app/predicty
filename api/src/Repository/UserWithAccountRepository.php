<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccountAwareUser;
use App\Entity\User;
use App\Entity\UserWithAccountContext;
use RuntimeException;

/**
 * This repository is used to get the user entity with the account context.
 */
class UserWithAccountRepository
{
    public function __construct(private UserRepository $userRepository, private AccountRepository $accountRepository)
    {
    }

    public function get(int $userId, int $accountId): AccountAwareUser&User
    {
        $user = $this->userRepository->getById($userId);
        $account = $this->accountRepository->getById($accountId);

        if (!$user->isMemberOf($account)) {
            throw new RuntimeException(sprintf('User "%s" is not a member of the account "%s"', $userId, $accountId));
        }

        return new UserWithAccountContext($user, $account);
    }
}
