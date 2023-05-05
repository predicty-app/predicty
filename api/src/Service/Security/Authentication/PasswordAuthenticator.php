<?php

declare(strict_types=1);

namespace App\Service\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @internal
 */
class PasswordAuthenticator extends GraphQLAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return isset($this->getCredentials($request)['password']);
    }

    public function authenticate(Request $request): Passport
    {
        ['username' => $username, 'password' => $password] = $this->getCredentials($request);

        return new Passport(new UserBadge($username), new PasswordCredentials($password));
    }
}
