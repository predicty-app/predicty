<?php

namespace App\Message;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

#[AsMessageHandler]
class LoginHandler
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private UserPasswordHasherInterface $passwordEncoder,
        private EventDispatcherInterface $eventDispatcher,
        private RequestStack $requestStack,
        private Security $security
    )
    {
    }

    /**
     * @todo login throttling
     */
    public function __invoke(Login $message): User
    {
        $user = $this->security->getUser();
        if($user === null) {
            try {
                $user = $this->userProvider->loadUserByIdentifier($message->username);
            } catch (UserNotFoundException $e) {
                throw new BadCredentialsException('Invalid username or password');
            }

            if (!$user instanceof PasswordAuthenticatedUserInterface) {
                throw new UnsupportedUserException('$user has to implements ' . PasswordAuthenticatedUserInterface::class);
            }

            if (!$this->passwordEncoder->isPasswordValid($user, $message->password)) {
                throw new BadCredentialsException('Invalid username or password');
            }

            $this->security->login($user, null, 'main');
            $request = $this->requestStack->getMainRequest();

            $event = new InteractiveLoginEvent($request, $this->security->getToken());
            $this->eventDispatcher->dispatch($event, 'security.interactive_login');
        }

        return $user;
    }
}