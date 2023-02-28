<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;

interface PasscodeVerifier
{
    public function verify(User $user, string $code): bool;
}
