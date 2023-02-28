<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\User;
use App\Message\Command\Login;
use App\Repository\UserRepository;
use App\Service\Security\PasscodeVerifier;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

#[AsMessageHandler]
class LoginHandler
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private UserRepository $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private PasscodeVerifier $passcodeVerifier,
        private RequestStack $requestStack,
        private Security $security
    ) {
    }

    /**
     * @todo login throttling
     * @todo move to an authenticator
     */
    public function __invoke(Login $message): User
    {
        try {
            $user = $this->userProvider->loadUserByIdentifier($message->username);
        } catch (UserNotFoundException $e) {
            throw new BadCredentialsException('Invalid username or passcode', 0, $e);
        }

        if (!$user instanceof User) {
            throw new UnsupportedUserException('Unsupported user type');
        }

        if ($this->security->getUser() === null) {
            if (!$this->passcodeVerifier->verify($user, $message->passcode)) {
                throw new BadCredentialsException('Invalid username or passcode');
            }

            $this->security->login($user, null, 'main');
            $request = $this->requestStack->getMainRequest();
            $token = $this->security->getToken();

            assert($request !== null);
            assert($token !== null);

            $event = new InteractiveLoginEvent($request, $token);
            $this->eventDispatcher->dispatch($event, 'security.interactive_login');

            // user used a passcode, therefore we also mark email as verified
            if ($user->isEmailVerified() === false) {
                $user->markEmailAsVerified();
                $this->userRepository->save($user);
            }
        }

        assert($user instanceof User);

        return $user;
    }
}
