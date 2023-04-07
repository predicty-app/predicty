<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Service\Security\Passcode\PasscodeVerifier;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @todo login throttling
 */
class DummyAuthenticator extends AbstractAuthenticator implements Authenticator
{
    private const SECRET_PASSCODE = 0;
    private const SECRET_PASSWORD = 1;

    public function __construct(
        private UserProviderInterface $userProvider,
        private PasscodeVerifier $passcodeVerifier,
        private Security $security,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function authenticateWithPasscode(string $username, string $passcode, ?callable $onSuccessCallback = null): User
    {
        return $this->verifyCredentialsAndLogin($username, $passcode, self::SECRET_PASSCODE, $onSuccessCallback);
    }

    public function authenticateWithPassword(string $username, string $password, ?callable $onSuccessCallback = null): User
    {
        return $this->verifyCredentialsAndLogin($username, $password, self::SECRET_PASSWORD, $onSuccessCallback);
    }

    public function supports(Request $request): ?bool
    {
        return false;
    }

    public function authenticate(Request $request): Passport
    {
        throw new RuntimeException('Request flow authentication is not supported by this authenticator');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }

    private function verifyCredentialsAndLogin(string $username, string $secret, int $type, ?callable $onSuccess = null): User
    {
        assert(in_array($type, [self::SECRET_PASSCODE, self::SECRET_PASSWORD], true));

        $user = $this->getUserByUsername($username);

        if (!$this->isLoggedIn()) {
            $isValid = match ($type) {
                self::SECRET_PASSCODE => $this->passcodeVerifier->isPasscodeValid($user, $secret),
                self::SECRET_PASSWORD => $this->userPasswordHasher->isPasswordValid($user, $secret),
                default => false
            };

            if (!$isValid) {
                throw new BadCredentialsException('Invalid credentials');
            }

            $this->security->login($user, null, 'main');

            if ($onSuccess !== null) {
                $onSuccess($user);
            }
        }

        return $user;
    }

    private function isLoggedIn(): bool
    {
        return $this->security->getUser() !== null;
    }

    private function getUserByUsername(string $username): User
    {
        try {
            $user = $this->userProvider->loadUserByIdentifier($username);
        } catch (UserNotFoundException $e) {
            throw new BadCredentialsException('Invalid username or passcode', 0, $e);
        }

        if (!$user instanceof User) {
            throw new UnsupportedUserException('Unsupported user type');
        }

        return $user;
    }
}
