<?php

declare(strict_types=1);

namespace App\Service\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\InteractiveAuthenticatorInterface;

/**
 * @internal
 */
abstract class GraphQLAuthenticator extends AbstractAuthenticator implements InteractiveAuthenticatorInterface
{
    public const GRAPHQL_CREDENTIALS_ATTRIBUTE_NAME = '_graphql_credentials';

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->clearCredentials($request);

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->clearCredentials($request);

        return null;
    }

    public function isInteractive(): bool
    {
        return true;
    }

    protected function getCredentials(Request $request): array
    {
        return $request->attributes->get(self::GRAPHQL_CREDENTIALS_ATTRIBUTE_NAME, []);
    }

    private function clearCredentials(Request $request): void
    {
        $request->attributes->remove(self::GRAPHQL_CREDENTIALS_ATTRIBUTE_NAME);
    }
}
