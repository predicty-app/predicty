<?php

declare(strict_types=1);

namespace App\Entity;

use Closure;
use DateTimeInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * This class is used to wrap the user entity with the account context.
 * You can use lazy loading to avoid initializing everything during object creation.
 * Use {@see UserWithAccountRepository} to get the user entity with the account context.
 *
 * @internal
 */
class UserWithAccountContext implements User, AccountAwareUser, WrappedUser, EquatableInterface
{
    public function __construct(private User|Closure $user, private Account|Closure $account)
    {
    }

    public static function wrap(User|Closure $user, Account|Closure $account): self
    {
        return new self($user, $account);
    }

    public function getUser(): User
    {
        if ($this->user instanceof Closure) {
            $user = ($this->user)();
            assert($user instanceof User, 'Lazily initialized user must be an instance of '.User::class);
            $this->user = $user;
        }

        return $this->user;
    }

    public function getAccount(): Account
    {
        if ($this->account instanceof Closure) {
            $account = ($this->account)();
            assert($account instanceof Account, 'Lazily initialized account must be an instance of '.Account::class);
            $this->account = $account;
        }

        return $this->account;
    }

    public function getId(): int
    {
        return $this->getUser()->getId();
    }

    public function getChangedAt(): DateTimeInterface
    {
        return $this->getUser()->getChangedAt();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->getUser()->getCreatedAt();
    }

    public function getUuid(): Uuid
    {
        return $this->getUser()->getUuid();
    }

    public function getEmail(): string
    {
        return $this->getUser()->getEmail();
    }

    public function isEmailVerified(): bool
    {
        return $this->getUser()->isEmailVerified();
    }

    public function isOnboardingComplete(): bool
    {
        return $this->getUser()->isOnboardingComplete();
    }

    public function isMemberOf(Account|int $account): bool
    {
        return $this->getUser()->isMemberOf($account);
    }

    public function getAccountsIds(): array
    {
        return $this->getUser()->getAccountsIds();
    }

    public function getRolesForAccount(Account|int $account): array
    {
        return $this->getUser()->getRolesForAccount($account);
    }

    public function getAccountId(): int
    {
        return $this->getAccount()->getId();
    }

    public function getAccountRoles(): array
    {
        return $this->getRolesForAccount($this->getAccountId());
    }

    public function hasAccountRole(string $role): bool
    {
        return in_array($role, $this->getAccountRoles(), true);
    }

    public function getRoles(): array
    {
        $roles = array_filter($this->getUser()->getRoles(), fn ($role) => Role::isSystemRole($role));
        $roles = array_unique(array_merge($roles, $this->getAccountRoles()));

        if ($roles === []) {
            $roles[] = Role::ROLE_USER;
        }

        return $roles;
    }

    public function eraseCredentials(): void
    {
        $this->getUser()->eraseCredentials();
    }

    public function getUserIdentifier(): string
    {
        return $this->getUser()->getUserIdentifier();
    }

    public function setAsAccountOwner(int $accountId): void
    {
        $this->getUser()->setAsAccountOwner($accountId);
    }

    public function setAsAccountMember(int $accountId): void
    {
        $this->getUser()->setAsAccountMember($accountId);
    }

    public function setEmailVerified(): void
    {
        $this->getUser()->setEmailVerified();
    }

    public function setOnboardingComplete(): void
    {
        $this->getUser()->setOnboardingComplete();
    }

    public function setPassword(string $password): void
    {
        $this->getUser()->setPassword($password);
    }

    public function getPassword(): ?string
    {
        return $this->getUser()->getPassword();
    }

    public function hasAgreedToNewsletter(): bool
    {
        return $this->getUser()->hasAgreedToNewsletter();
    }

    public function setAgreedToNewsletter(): void
    {
        $this->getUser()->setAgreedToNewsletter();
    }

    public function hasAgreedToTerms(int $version): bool
    {
        return $this->getUser()->hasAgreedToTerms($version);
    }

    public function setAgreedToTerms(int $version): void
    {
        $this->getUser()->setAgreedToTerms($version);
    }

    public function getAcceptedTermsOfServiceVersion(): int
    {
        return $this->getUser()->getAcceptedTermsOfServiceVersion();
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return
            $this->getUserIdentifier() === $user->getUserIdentifier() &&
            $this->getRoles() === $user->getRoles();
    }
}
