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
#[ORM\Index(fields: ['name'])]
#[ORM\Index(fields: ['startedAt'])]
class AdCollection implements Ownable, BelongsToAccount
{
    use BelongsToAccountTrait;
    use IdTrait;
    use OwnableTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private array $adsIds = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $endedAt;

    public function __construct(
        int $userId,
        int $accountId,
        string $name,
        array $adsIds = [],
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->adsIds = $adsIds;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
        $this->accountId = $accountId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int>
     */
    public function getAdsIds(): array
    {
        return $this->adsIds;
    }

    public function addAdsIds(array $adsIds): void
    {
        $this->adsIds = array_unique(array_merge($this->adsIds, $adsIds));
    }

    public function removeAdsIds(array $adsIds): void
    {
        $this->adsIds = array_diff($this->adsIds, $adsIds);
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getEndedAt(): ?DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setStartedAt(DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function setEndedAt(DateTimeImmutable $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->userId === $user->getId();
    }
}
