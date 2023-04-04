<?php

declare(strict_types=1);

namespace App\Entity;

class ImportResult
{
    public function __construct(
        private int $createdCampaigns = 0,
        private int $createdAdSets = 0,
        private int $createdAds = 0,
        private int $createdAdStats = 0,
        private int $createdDailyRevenues = 0,
    ) {
    }

    public function getTotalCreated(): int
    {
        return $this->createdCampaigns + $this->createdAdSets + $this->createdAds + $this->createdAdStats + $this->createdDailyRevenues;
    }

    public function getCreatedCampaigns(): int
    {
        return $this->createdCampaigns;
    }

    public function getCreatedAdSets(): int
    {
        return $this->createdAdSets;
    }

    public function getCreatedAds(): int
    {
        return $this->createdAds;
    }

    public function getCreatedAdStats(): int
    {
        return $this->createdAdStats;
    }

    public function getCreatedDailyRevenues(): int
    {
        return $this->createdDailyRevenues;
    }

    public function incrementCreatedCampaigns(): void
    {
        ++$this->createdCampaigns;
    }

    public function incrementCreatedAdSets(): void
    {
        ++$this->createdAdSets;
    }

    public function incrementCreatedAds(): void
    {
        ++$this->createdAds;
    }

    public function incrementCreatedAdStats(): void
    {
        ++$this->createdAdStats;
    }

    public function incrementCreatedDailyRevenues(): void
    {
        ++$this->createdDailyRevenues;
    }

    public function toArray(): array
    {
        return [
            'createdCampaigns' => $this->createdCampaigns,
            'createdAdSets' => $this->createdAdSets,
            'createdAds' => $this->createdAds,
            'createdAdStats' => $this->createdAdStats,
            'createdDailyRevenues' => $this->createdDailyRevenues,
        ];
    }

    public static function empty(): self
    {
        return new self();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            createdCampaigns: $data['createdCampaigns'] ?? 0,
            createdAdSets: $data['createdAdSets'] ?? 0,
            createdAds: $data['createdAds'] ?? 0,
            createdAdStats: $data['createdAdStats'] ?? 0,
            createdDailyRevenues: $data['createdDailyRevenues'] ?? 0,
        );
    }
}
