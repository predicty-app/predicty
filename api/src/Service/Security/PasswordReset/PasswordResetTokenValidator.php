<?php

declare(strict_types=1);

namespace App\Service\Security\PasswordReset;

use Symfony\Component\Uid\Ulid;

interface PasswordResetTokenValidator
{
    /**
     * If provided token is valid, it will be removed afterwards.
     */
    public function validateAndGetUserId(string $token): ?Ulid;
}
