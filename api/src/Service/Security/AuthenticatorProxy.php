<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;

/**
 * Api for interacting with the authenticator system.
 */
class AuthenticatorProxy
{
    public function __construct(
        private RequestStack $requestStack,
        private AuthenticatorManagerInterface $authenticatorManager,
        private Security $security,
    ) {
    }

    public function authenticateWithPasscode(string $username, string $passcode, ?callable $onSuccessCallback = null): User
    {
        return $this->authenticate([
            'username' => $username,
            'passcode' => $passcode,
        ], $onSuccessCallback);
    }

    public function authenticateWithPassword(string $username, string $password, ?callable $onSuccessCallback = null): User
    {
        return $this->authenticate([
            'username' => $username,
            'password' => $password,
        ], $onSuccessCallback);
    }

    private function authenticate(array $credentials, ?callable $onSuccess = null): User
    {
        $request = $this->requestStack->getMainRequest();
        assert($request !== null);

        $request->attributes->set(GraphQLAuthenticator::GRAPHQL_CREDENTIALS_ATTRIBUTE_NAME, $credentials);

        $this->authenticatorManager->supports($request);
        $this->authenticatorManager->authenticateRequest($request);
        $user = $this->security->getUser();
        assert($user instanceof User);

        if ($onSuccess !== null) {
            $onSuccess($user);
        }

        return $user;
    }
}
