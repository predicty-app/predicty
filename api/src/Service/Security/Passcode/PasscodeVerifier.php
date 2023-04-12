<?php

declare(strict_types=1);

namespace App\Service\Security\Passcode;

use App\Entity\User;

interface PasscodeVerifier
{
    /**
     * This method should remove the passcode after checking it, therefore it is not idempotent.
     */
    public function isPasscodeValid(User $user, string $code): bool;
}
