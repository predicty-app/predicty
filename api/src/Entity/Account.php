<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
class Account implements Ownable
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(unique: false)]
    private int $userId;

    #[ORM\Column]
    private ?string $name = null;

    public function __construct(int $userId, string $name = null)
    {
        $this->userId = $userId;
        $this->name = $name ?? '';
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
        $this->changedAt = Clock::now();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->getUserId() === $user->getId();
    }
}
