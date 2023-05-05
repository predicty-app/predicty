<?php

declare(strict_types=1);

namespace App\Service\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Updates roles stored in the token storage.
 * This is a workaround for a symfony quirk that logs out users after role change.
 * The trick is to update the role in the same request.
 *
 * WARNING: after updating role, it will take effect immediately during current request,
 * therefore no other operations relying on user roles nor permissions should be done.
 */
class UserRoleUpdater
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function update(UserInterface $user, string $firewallName = 'main'): void
    {
        $this->tokenStorage->setToken(new PostAuthenticationToken($user, $firewallName, $user->getRoles()));
    }
}
