<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Account;
use App\Entity\Permission;
use App\Entity\UserWithAccountContext;
use App\GraphQL\TypeRegistry;
use App\Repository\ConnectedAccountRepository;
use App\Repository\UserRepository;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class AccountType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        private CurrentUser $currentUser,
        private UserRepository $userRepository,
        private ConnectedAccountRepository $connectedAccountRepository,
    ) {
        parent::__construct([
            'name' => 'Account',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'name' => [
                    'type' => $type->string(),
                ],
                'currency' => [
                    'type' => $type->string(),
                    'resolve' => fn (Account $account) => 'PLN',
                ],
                'connectedAccounts' => fn () => [
                    'type' => $type->listOf($type->connectedAccount()),
                    'resolve' => function (Account $account) {
                        if ($this->currentUser->isAllowedTo(Permission::MANAGE_ACCOUNT, $account)) {
                            foreach ($this->connectedAccountRepository->findAllByAccountId($account->getId()) as $connectedAccount) {
                                yield $connectedAccount;
                            }
                        }
                    },
                    'description' => 'External accounts connected to this account',
                ],
                'users' => fn () => [
                    'type' => $type->listOf($type->user()),
                    'resolve' => function (Account $account) {
                        if ($this->currentUser->isAllowedTo(Permission::MANAGE_ACCOUNT, $account)) {
                            foreach ($this->userRepository->findByAccountId($account->getId()) as $user) {
                                yield new UserWithAccountContext($user, $account);
                            }
                        }
                    },
                    'description' => 'Users that are members of this account',
                ],
                'createdAt' => [
                    'type' => $type->dateTime(),
                ],
                'changedAt' => [
                    'type' => $type->dateTime(),
                ],
            ],
        ]);
    }
}
