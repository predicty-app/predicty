<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\User;
use App\GraphQL\TypeRegistry;
use App\Repository\ConnectedAccountRepository;
use GraphQL\Type\Definition\ObjectType;

class UserType extends ObjectType
{
    public function __construct(TypeRegistry $type, ConnectedAccountRepository $connectedAccountRepository)
    {
        parent::__construct([
            'name' => 'User',
            'fields' => [
                'id' => [
                    'type' => $type->int(),
                ],
                'uid' => [
                    'type' => $type->id(),
                    'resolve' => fn (User $user) => (string) $user->getUuid(),
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
                'connectedAccounts' => [
                    'type' => $type->listOf($type->connectedAccount()),
                    'resolve' => fn (User $user) => $connectedAccountRepository->findAll($user->getId()),
                ],
            ],
        ]);
    }
}
