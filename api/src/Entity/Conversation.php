<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['date'])]
#[ORM\UniqueConstraint(fields: ['userId', 'accountId', 'date'])]
class Conversation implements Ownable, BelongsToAccount
{
    use BelongsToAccountTrait;
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private string $color;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    public function __construct(int $userId, int $accountId, DateTimeImmutable $date, Color $color)
    {
        $this->userId = $userId;
        $this->color = $color->toHexString();
        $this->date = $date;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->accountId = $accountId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getColor(): Color
    {
        return Color::fromString($this->color);
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->userId === $user->getId();
    }
}
