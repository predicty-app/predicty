<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;

interface Authenticator
{
    public function authenticateWithPasscode(string $username, string $passcode, ?callable $onSuccessCallback = null): User;

    public function authenticateWithPassword(string $username, string $password, ?callable $onSuccessCallback = null): User;
}
