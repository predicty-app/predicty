<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use LogicException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Throwable;

/**
 * @internal
 */
class AuthenticationResultListener
{
    private ?User $user = null;
    private ?Throwable $exception = null;

    #[AsEventListener]
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        assert($user instanceof User);
        $this->user = $user;
    }

    #[AsEventListener]
    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $this->exception = $event->getException();
    }

    public function getResult(): User|Throwable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if ($this->exception !== null) {
            return $this->exception;
        }

        throw new LogicException('No authentication result available - make sure that the authenticator is configured correctly');
    }
}
