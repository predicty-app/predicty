<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

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
                ],
                'email' => [
                    'type' => Type::string(),
                ],
                'is_email_verified' => [
                    'type' => Type::boolean(),
                ],
            ],
        ]);
    }
}
