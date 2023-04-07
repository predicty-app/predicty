<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\User;
use App\Message\Command\Login;
use App\Repository\UserRepository;
use App\Service\Security\AuthenticatorProxy;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LoginHandler
{
    public function __construct(private AuthenticatorProxy $authenticatorProxy, private UserRepository $userRepository)
    {
    }

    public function __invoke(Login $message): User
    {
        return $this->authenticatorProxy->authenticateWithPasscode(
            $message->username,
            $message->passcode,
            function (User $user): void {
                if ($user->isEmailVerified() === false) {
                    $user->setEmailVerified();
                    $this->userRepository->save($user);
                }
            }
        );
    }
}
