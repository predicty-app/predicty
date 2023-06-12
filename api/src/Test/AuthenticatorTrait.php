<?php

declare(strict_types=1);

namespace App\Test;

use App\DataFixtures\UserFixture;
use App\Entity\Account;
use App\Entity\AccountMember;
use App\Entity\UserWithId;
use App\Service\Security\Account\AccountSwitcher;
use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Uid\Ulid;

trait AuthenticatorTrait
{
    use KernelBrowserTrait;

    /**
     * Authenticate the user with the given identifier.
     * By default, you are authenticated as John Doe.
     *
     * @see UserFixture::JOHN
     */
    public static function authenticate(UserInterface|string $userOrIdentifier = 'john.doe@example.com'): void
    {
        if ($userOrIdentifier instanceof UserInterface) {
            $userOrIdentifier = $userOrIdentifier->getUserIdentifier();
        }

        $client = static::getClient();

        /** @var UserProviderInterface $users */
        $users = $client->getContainer()->get(UserProviderInterface::class);
        $user = $users->loadUserByIdentifier($userOrIdentifier);
        $client->loginUser($user);
    }

    public static function getUser(): ?UserInterface
    {
        return static::getClient()->getContainer()->get('security.token_storage')->getToken()?->getUser();
    }

    /**
     * Switches currently used account to the given one.
     */
    public static function switchAccount(Ulid|Account $account): void
    {
        $user = static::getUser();

        if ($user instanceof AccountMember && $user instanceof UserWithId) {
            /** @var AccountSwitcher $switcher */
            $switcher = static::getContainer()->get(AccountSwitcher::class);
            /** @noinspection PhpParamsInspection */
            $switcher->switchAccount($user, $account);

            return;
        }

        throw new InvalidArgumentException('Unable to switch user in test - either the user is not logged in or is of a wrong type.');
    }
}
