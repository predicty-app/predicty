<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\User;
use App\GraphQL\TypeRegistry;
use App\Repository\AccountRepository;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class UserType extends ObjectType
{
    public function __construct(TypeRegistry $type, AccountRepository $accountRepository)
    {
        parent::__construct([
            'name' => 'User',
            'fields' => [
                'id' => [
                    'type' => $type->int(),
                ],
                'uid' => [
                    'type' => $type->id(),
                    'resolve' => fn (User $user) => $user->getUuid()->toBase58(),
                ],
                'email' => [
                    'type' => $type->string(),
                ],
                'isEmailVerified' => [
                    'type' => $type->boolean(),
                ],
                'isOnboardingComplete' => [
                    'type' => $type->boolean(),
                ],
                'currentAccount' => fn () => [
                    'type' => $type->account(),
                    'resolve' => fn (User $user) => $user instanceof CurrentUser ? $user->getAccount() : null,
                    'description' => 'Account that is currently used by the user',
                ],
                'accounts' => fn () => [
                    'type' => $type->listOf($type->account()),
                    'resolve' => fn (User $user) => $accountRepository->findAllByIds($user->getAccountsIds()),
                    'description' => 'All accounts that the user is a member of',
                ],
                'roles' => [
                    'type' => $type->listOf($type->string()),
                    'resolve' => fn (User $user) => $user->getRoles(),
                ],
            ],
        ]);
    }
}
