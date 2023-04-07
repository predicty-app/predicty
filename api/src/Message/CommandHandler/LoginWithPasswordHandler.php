<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\User;
use App\Message\Command\Login;
use App\Message\Command\LoginWithPassword;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

#[AsMessageHandler]
class LoginWithPasswordHandler
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private EventDispatcherInterface $eventDispatcher,
        private RequestStack $requestStack,
        private Security $security,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @todo login throttling
     * @todo move to an authenticator
     */
    public function __invoke(LoginWithPassword $message): User
    {
        try {
            $user = $this->userProvider->loadUserByIdentifier($message->username);
        } catch (UserNotFoundException $e) {
            throw new BadCredentialsException('Invalid username or password', 0, $e);
        }

        if (!$user instanceof User) {
            throw new UnsupportedUserException('Unsupported user type');
        }

        if ($this->security->getUser() === null) {
            if (!$this->userPasswordHasher->isPasswordValid($user, $message->password)) {
                throw new BadCredentialsException('Invalid username or password');
            }

            $this->security->login($user, null, 'main');
            $request = $this->requestStack->getMainRequest();
            $token = $this->security->getToken();

            assert($request !== null);
            assert($token !== null);

            $event = new InteractiveLoginEvent($request, $token);
            $this->eventDispatcher->dispatch($event, 'security.interactive_login');
        }

        assert($user instanceof User);

        return $user;
    }
}
