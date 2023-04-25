<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['date'])]
#[ORM\UniqueConstraint(fields: ['userId', 'date'])]
class Conversation implements UserOwnedEntity
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private string $color;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    public function __construct(int $userId, DateTimeImmutable $date, Color $color)
    {
        $this->userId = $userId;
        $this->color = $color->toHexString();
        $this->date = $date;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
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

    public function isOwnedBy(User $user): bool
    {
        return $this->userId === $user->getId();
    }
}
