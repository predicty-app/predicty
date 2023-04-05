<?php

declare(strict_types=1);

namespace App\Service\Security\PasswordReset;

interface ResetPasswordTokenValidator
{
    /**
     * If provided token is valid, it will be removed afterwards.
     */
    public function validateAndGetUserId(string $token): ?int;
}
