<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @internal you probably want to use the {@see User} instead
 */
#[ORM\Entity]
#[ORM\Index(fields: ['email'])]
#[ORM\Table(name: '`user`')]
class DoctrineUser implements UserInterface, EmailRecipient, PasswordAuthenticatedUser, UserWithId, User
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private array $accountIds = [];

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password = '';

    #[ORM\Column(options: ['default' => false])]
    private bool $isEmailVerified = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $isOnboardingComplete = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $hasAgreedToNewsletter = false;

    #[ORM\Column(options: ['default' => 0])]
    private int $acceptedTermsOfServiceVersion = 0;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
        $this->changedAt = Clock::now();
    }

    public function getUuid(): Uuid
    {
        Assert::notNull($this->uuid);

        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->changedAt = Clock::now();

        return $this;
    }

    public function setAsAccountOwner(int $accountId): void
    {
        $this->setAccountRole($accountId, Role::ROLE_ACCOUNT_OWNER);
    }

    public function setAsAccountMember(int $accountId): void
    {
        $this->setAccountRole($accountId, Role::ROLE_ACCOUNT_MEMBER);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        if ($this->roles === []) {
            return [Role::ROLE_USER];
        }

        return array_unique($this->roles);
    }

    public function getRolesForAccount(Account|int $account): array
    {
        $roles = [];
        $accountId = $account instanceof Account ? $account->getId() : $account;
        foreach ($this->accountIds as $account) {
            if ($account['id'] === $accountId) {
                $roles = array_merge($roles, $account['roles']);
            }
        }

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
        $this->changedAt = Clock::now();
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->isEmailVerified;
    }

    public function setEmailVerified(): void
    {
        $this->isEmailVerified = true;
        $this->changedAt = Clock::now();
    }

    public function hasAgreedToNewsletter(): bool
    {
        return $this->hasAgreedToNewsletter;
    }

    public function setAgreedToNewsletter(): void
    {
        $this->hasAgreedToNewsletter = true;
        $this->changedAt = Clock::now();
    }

    public function hasAgreedToTerms(int $version): bool
    {
        return $this->acceptedTermsOfServiceVersion === $version;
    }

    public function setAgreedToTerms(int $version): void
    {
        $this->acceptedTermsOfServiceVersion = $version;
        $this->changedAt = Clock::now();
    }

    public function getAcceptedTermsOfServiceVersion(): int
    {
        return $this->acceptedTermsOfServiceVersion;
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->isOnboardingComplete;
    }

    public function setOnboardingComplete(): void
    {
        $this->isOnboardingComplete = true;
        $this->changedAt = Clock::now();
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isMemberOf(Account|int $account): bool
    {
        $accountId = $account instanceof Account ? $account->getId() : $account;

        return in_array($accountId, $this->getAccountsIds(), true);
    }

    /**
     * Get all account ids the user is a member of.
     *
     * @return array<int>
     */
    public function getAccountsIds(): array
    {
        $ids = [];
        foreach ($this->accountIds as $account) {
            $ids[] = (int) $account['id'];
        }

        return $ids;
    }

    public function setAccountRole(int $accountId, string $role): void
    {
        foreach ($this->accountIds as $index => $account) {
            if ($account['id'] === $accountId) {
                $this->accountIds[$index]['roles'] = [$role];

                return;
            }
        }

        $this->accountIds[] = ['id' => $accountId, 'roles' => [$role]];
        $this->changedAt = Clock::now();
    }
}
