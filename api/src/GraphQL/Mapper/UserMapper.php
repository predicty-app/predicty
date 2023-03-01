<?php

declare(strict_types=1);

namespace App\GraphQL\Mapper;

use App\Entity\User;

class UserMapper
{
    public function map(User $user): array
    {
        return [
            'uid' => (string) $user->getUuid(),
            'email' => $user->getEmail(),
            'is_email_verified' => $user->isEmailVerified(),
        ];
    }
}
