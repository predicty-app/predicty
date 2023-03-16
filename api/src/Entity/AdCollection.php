<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AdCollection
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private array $adsIds = [];

    public function __construct(
        int $userId,
        string $name,
        array $adsIds = [],
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $changedAt = null
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->adsIds = $adsIds;
        $this->createdAt = $createdAt ?? Clock::now();
        $this->changedAt = $changedAt ?? Clock::now();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addAdId(int $adId): void
    {
        $this->adsIds[] = $adId;
        $this->adsIds = array_unique($this->adsIds);
    }

    /**
     * @return array<int>
     */
    public function getAdsIds(): array
    {
        return $this->adsIds;
    }

    public function setAdsIds(array $adsIds): self
    {
        $this->adsIds = $adsIds;

        return $this;
    }

    public function addAdsIds(array $adsIds): void
    {
        $this->adsIds = array_unique(array_merge($this->adsIds, $adsIds));
    }
}
