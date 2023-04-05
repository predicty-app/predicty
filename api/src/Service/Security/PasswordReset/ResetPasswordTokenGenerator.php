<?php

declare(strict_types=1);

namespace App\Service\Security\PasswordReset;

use App\Entity\User;

interface ResetPasswordTokenGenerator
{
    public function createToken(User $user): string;
}
