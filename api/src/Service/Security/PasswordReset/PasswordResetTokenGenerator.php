<?php

declare(strict_types=1);

namespace App\Service\Security\PasswordReset;

use App\Entity\User;

interface PasswordResetTokenGenerator
{
    public function createToken(User $user): string;
}
