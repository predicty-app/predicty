<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Service\Clock\Clock;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @internal you probably want to use the {@see User} instead
 */
#[ORM\Entity]
#[ORM\Index(fields: ['email'])]
#[ORM\Table(name: '`user`')]
class DoctrineUser implements User
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private array $accounts = [];

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

    public function __construct(Ulid $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
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

    public function setAsAccountOwner(Ulid|Account $accountId): void
    {
        $accountId = $accountId instanceof Account ? $accountId->getId() : $accountId;
        $this->setAccountRole($accountId, Role::ROLE_ACCOUNT_OWNER);
    }

    public function setAsAccountMember(Ulid|Account $accountId): void
    {
        $accountId = $accountId instanceof Account ? $accountId->getId() : $accountId;
        $this->setAccountRole($accountId, Role::ROLE_ACCOUNT_MEMBER);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->getEmail();
    }

    public function getRoles(): array
    {
        if ($this->roles === []) {
            return [Role::ROLE_USER];
        }

        return array_unique($this->roles);
    }

    public function getRolesForAccount(Account|Ulid $accountId): array
    {
        $roles = [];
        $accountId = $accountId instanceof Account ? $accountId->getId() : $accountId;
        foreach ($this->accounts as $account) {
            if ($account['id'] === (string) $accountId) {
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

    public function isMemberOf(Account|Ulid $account): bool
    {
        $accountId = $account instanceof Account ? $account->getId() : $account;

        foreach ($this->getAccountsIds() as $id) {
            if ($id->equals($accountId)) {
                return true;
            }
        }

        return false;
    }

    public function getAccountsIds(): array
    {
        return array_map(fn (array $account) => Ulid::fromString($account['id']), $this->accounts);
    }

    public function setAccountRole(Ulid|Account $accountId, string $role): void
    {
        $accountId = $accountId instanceof Account ? $accountId->getId() : $accountId;

        foreach ($this->accounts as $index => $account) {
            if ($account['id'] === (string) $accountId) {
                $this->accounts[$index]['roles'] = [$role];

                return;
            }
        }

        $this->accounts[] = ['id' => (string) $accountId, 'roles' => [$role]];
        $this->changedAt = Clock::now();
    }
}
