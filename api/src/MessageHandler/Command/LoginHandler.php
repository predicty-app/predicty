<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\User;
use App\Message\Command\Login;
use App\Repository\UserRepository;
use App\Service\Security\Authentication\Authenticator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LoginHandler
{
    public function __construct(private Authenticator $authenticator, private UserRepository $userRepository)
    {
    }

    public function __invoke(Login $message): User
    {
        return $this->authenticator->authenticateWithPasscode(
            $message->username,
            $message->passcode,
            function (User $user): void {
                if ($user->hasVerifiedEmail() === false) {
                    $user->setEmailVerified();
                    $this->userRepository->save($user);
                }
            }
        );
    }
}
