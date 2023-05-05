<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\Account;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserWithAccountContext;
use App\Entity\UserWithId;
use App\Service\Security\Account\AccountContextProvider;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * @internal This class should only be used by the Security component. {@link CurrentUser} should be used instead.
 */
class CurrentUserService extends UserWithAccountContext implements CurrentUser
{
    public function __construct(
        private Security $security,
        private AccountContextProvider $currentAccountContext
    ) {
        parent::__construct(fn () => $this->lazilyLoadUser(), fn () => $this->lazilyLoadAccount());
    }

    public function isAnonymous(): bool
    {
        return $this->security->isGranted(Role::IS_AUTHENTICATED) === false;
    }

    public function isAllowedTo(string $permission, mixed $subject): bool
    {
        return $this->security->isGranted($permission, $subject);
    }

    public function getAccountRoles(): array
    {
        if ($this->isAnonymous()) {
            return [];
        }

        return $this->getUser()->getRolesForAccount($this->getAccountId());
    }

    public function isTheSameUserAs(UserWithId $user): bool
    {
        return !$this->isAnonymous() && $this->getId() === $user->getId();
    }

    private function lazilyLoadUser(): User
    {
        $user = $this->security->getUser();

        if ($user === null) {
            $exception = new CustomUserMessageAuthenticationException('User was requested but none was logged in');
            $exception->setSafeMessage('You are not logged in');

            throw $exception;
        }

        if (!$user instanceof User) {
            throw new RuntimeException(sprintf('CurrentUserService requires the security user to implement the UserInterface, instead it got %s', get_debug_type($user)));
        }

        return $user;
    }

    private function lazilyLoadAccount(): Account
    {
        $account = $this->currentAccountContext->getCurrentlySelectedAccount($this->getUser());

        if ($account === null) {
            $exception = new CustomUserMessageAuthenticationException('Account was requested but none was found');
            $exception->setSafeMessage('You do not have access to any account');

            throw $exception;
        }

        return $account;
    }
}
