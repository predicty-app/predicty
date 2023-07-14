<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['email'])]
class AccountInvitation implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private string $email;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $validTo;

    public function __construct(Ulid $id, Ulid $userId, Ulid $accountId, string $email, DateTimeImmutable $validTo)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->email = $email;
        $this->validTo = $validTo;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getValidTo(): DateTimeImmutable
    {
        return $this->validTo;
    }
}
