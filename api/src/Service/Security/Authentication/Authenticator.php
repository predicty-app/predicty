<?php

declare(strict_types=1);

namespace App\Service\Security\Authentication;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;

/**
 * Api for interacting with the authenticator system.
 */
class Authenticator
{
    public function __construct(
        private RequestStack $requestStack,
        private AuthenticatorManagerInterface $authenticatorManager,
        private AuthenticationResultListener $authenticationResultListener,
        private Security $security,
    ) {
    }

    public function authenticateWithPasscode(string $username, string $passcode, ?callable $onSuccess = null): User
    {
        return $this->authenticate([
            'username' => $username,
            'passcode' => $passcode,
        ], $onSuccess);
    }

    public function authenticateWithPassword(string $username, string $password, ?callable $onSuccess = null): User
    {
        return $this->authenticate([
            'username' => $username,
            'password' => $password,
        ], $onSuccess);
    }

    private function authenticate(array $credentials, ?callable $onSuccess = null): User
    {
        if ($this->isLoggedIn()) {
            $exception = new CustomUserMessageAuthenticationException('User is already logged in');
            $exception->setSafeMessage('Already logged in');

            throw $exception;
        }

        $request = $this->requestStack->getMainRequest();
        assert($request !== null);

        $request->attributes->set(GraphQLAuthenticator::GRAPHQL_CREDENTIALS_ATTRIBUTE_NAME, $credentials);
        $this->runAuthenticationLogic($request);

        $result = $this->authenticationResultListener->getResult();

        if ($result instanceof User) {
            if ($onSuccess !== null) {
                $onSuccess($result);
            }

            return $result;
        }

        throw $result;
    }

    private function runAuthenticationLogic(Request $request): void
    {
        $this->authenticatorManager->supports($request);
        $this->authenticatorManager->authenticateRequest($request);
    }

    private function isLoggedIn(): bool
    {
        return $this->security->getUser() instanceof User;
    }
}
