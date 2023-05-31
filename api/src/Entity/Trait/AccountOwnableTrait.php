<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Entity\Account;
use App\Entity\AccountMember;
use App\Entity\AccountOwnable;
use AssertionError;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

trait AccountOwnableTrait
{
    #[ORM\Column(type: UlidType::NAME, nullable: true)]
    private ?Ulid $accountId = null;

    public function getAccountId(): Ulid
    {
        if ($this->accountId === null) {
            throw new AssertionError(sprintf('Entity "%s" is not associated with any account', (new ReflectionClass($this))->getShortName()));
        }

        return $this->accountId;
    }

    public function belongsToAccount(Account|Ulid $accountId): bool
    {
        if ($accountId instanceof Account) {
            $accountId = $this->getAccountId();
        }

        return $this->getAccountId()->equals($accountId);
    }

    public function belongsToSameAccount(AccountOwnable|AccountMember $entity): bool
    {
        if ($entity instanceof AccountMember) {
            return $entity->isMemberOf($this->getAccountId());
        }

        return $this->getAccountId()->equals($entity->getAccountId());
    }
}
