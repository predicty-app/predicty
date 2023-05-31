<?php

declare(strict_types=1);

namespace App\Entity;

interface UserOwnable
{
    public function isOwnedBy(UserWithId $user): bool;
}
