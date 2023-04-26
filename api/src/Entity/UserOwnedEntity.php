<?php

declare(strict_types=1);

namespace App\Entity;

interface UserOwnedEntity
{
    public function getUserId(): int;

    public function isOwnedBy(User $user): bool;
}
