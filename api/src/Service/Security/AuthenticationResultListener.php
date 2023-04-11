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
    private User|Throwable|null $result = null;

    #[AsEventListener]
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        assert($user instanceof User);
        $this->result = $user;
    }

    #[AsEventListener]
    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $this->result = $event->getException();
    }

    public function getResult(): User|Throwable
    {
        if ($this->result instanceof User || $this->result instanceof Throwable) {
            return $this->result;
        }

        throw new LogicException('No authentication result available - make sure that the authenticator is configured correctly');
    }
}
