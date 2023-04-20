<?php

declare(strict_types=1);

namespace App\Test;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;

trait AuthenticatorTrait
{
    use KernelBrowserTrait;

    public static function authenticate(User|string $userOrIdentifier = null): void
    {
        if ($userOrIdentifier === null) {
            $userOrIdentifier = 'john.doe@example.com';
        }

        if ($userOrIdentifier instanceof User) {
            $userOrIdentifier = $userOrIdentifier->getEmail();
        }

        $client = static::getClient();

        /** @var UserProviderInterface $users */
        $users = static::getContainer()->get(UserProviderInterface::class);
        $user = $users->loadUserByIdentifier($userOrIdentifier);
        $client->loginUser($user);
    }
}
