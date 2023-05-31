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
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['date'])]
#[ORM\UniqueConstraint(fields: ['userId', 'accountId', 'date'])]
class Conversation implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private string $color;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    public function __construct(Ulid $id, Ulid $userId, Ulid $accountId, DateTimeImmutable $date, Color $color)
    {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->color = $color->toHexString();
        $this->date = $date;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getColor(): Color
    {
        return Color::fromString($this->color);
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
