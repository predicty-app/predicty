<?php

declare(strict_types=1);

namespace App\Entity;

use AssertionError;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;

trait BelongsToAccountTrait
{
    #[ORM\Column(nullable: true)]
    private ?int $accountId = null;

    public function getAccountId(): int
    {
        if ($this->accountId === null) {
            throw new AssertionError(sprintf('Entity "%s" is not associated with any account', (new ReflectionClass($this))->getShortName()));
        }

        return $this->accountId;
    }

    public function belongsToAccount(Account|int $accountId): bool
    {
        if ($accountId instanceof Account) {
            return $this->getAccountId() === $accountId->getId();
        }

        return $this->getAccountId() === $accountId;
    }

    public function belongsToSameAccount(BelongsToAccount|AccountMember $entity): bool
    {
        if ($entity instanceof AccountMember) {
            return $entity->isMemberOf($this->getAccountId());
        }

        return $this->getAccountId() === $entity->getAccountId();
    }
}
