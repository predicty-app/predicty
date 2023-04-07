<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\User;
use App\Message\Command\LoginWithPassword;
use App\Service\Security\AuthenticatorProxy;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LoginWithPasswordHandler
{
    public function __construct(private AuthenticatorProxy $authenticatorProxy)
    {
    }

    public function __invoke(LoginWithPassword $message): User
    {
        return $this->authenticatorProxy->authenticateWithPassword($message->username, $message->password);
    }
}
