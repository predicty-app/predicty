<?php

namespace App\GraphQL\Mapper;

use App\Entity\User;

class UserMapper
{
    public function toArray(User $user): array
    {
        return [
            'uid' => (string) $user->getUuid(),
            'email' => $user->getEmail(),
        ];
    }
}