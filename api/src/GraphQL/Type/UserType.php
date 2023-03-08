<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'User',
            'fields' => [
                'uid' => [
                    'type' => Type::id(),
                    'resolve' => fn (User $user) => $user->getUuid()->__toString(),
                ],
                'email' => [
                    'type' => Type::string(),
                ],
                'isEmailVerified' => [
                    'type' => Type::boolean(),
                ],
            ],
        ]);
    }
}
