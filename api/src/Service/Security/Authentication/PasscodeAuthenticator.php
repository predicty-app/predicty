<?php

declare(strict_types=1);

namespace App\Service\Security\Authentication;

use App\Entity\User;
use App\Service\Security\Passcode\PasscodeVerifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @internal
 */
class PasscodeAuthenticator extends GraphQLAuthenticator
{
    public function __construct(private PasscodeVerifier $passcodeVerifier)
    {
    }

    public function supports(Request $request): ?bool
    {
        return isset($this->getCredentials($request)['passcode']);
    }

    public function authenticate(Request $request): Passport
    {
        ['username' => $username, 'passcode' => $passcode] = $this->getCredentials($request);
        $credentialsBadge = new CustomCredentials(fn (string $passcode, User $user) => $this->passcodeVerifier->isPasscodeValid($user, $passcode), $passcode);

        return new Passport(new UserBadge($username), $credentialsBadge);
    }
}
