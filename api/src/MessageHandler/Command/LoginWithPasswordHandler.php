<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\User;
use App\Message\Command\LoginWithPassword;
use App\Service\Security\Authentication\Authenticator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LoginWithPasswordHandler
{
    public function __construct(private Authenticator $authenticator)
    {
    }

    public function __invoke(LoginWithPassword $message): User
    {
        return $this->authenticator->authenticateWithPassword($message->username, $message->password);
    }
}
