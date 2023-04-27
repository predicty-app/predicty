<?php

declare(strict_types=1);

namespace App\Entity;

interface Ownable
{
    public function isOwnedBy(UserWithId $user): bool;
}
