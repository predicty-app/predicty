<?php

declare(strict_types=1);

namespace App\Service\Security\Passcode;

use App\Entity\User;

interface PasscodeGenerator
{
    /**
     * @return string 6-digit authentication code (000000)
     */
    public function generate(User $user, int $ttl = 300): string;
}
