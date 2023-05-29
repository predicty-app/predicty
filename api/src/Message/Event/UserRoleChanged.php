<?php

declare(strict_types=1);

namespace App\Message\Event;

class UserRoleChanged
{
    public function __construct(
        public int $actingUserId,
        public int $affectedUserId,
        public int $accountId,
        public string $role
    ) {
    }
}
