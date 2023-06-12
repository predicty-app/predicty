<?php

declare(strict_types=1);

namespace App\Message\Event;

use Symfony\Component\Uid\Ulid;

class UserRoleChanged
{
    public function __construct(
        public Ulid $actingUserId,
        public Ulid $affectedUserId,
        public Ulid $accountId,
        public string $role
    ) {
    }
}
