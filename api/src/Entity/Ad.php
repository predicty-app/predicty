<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AdRepository;
use Brick\Money\Currency;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdRepository::class)]
class Ad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private int $adSetId;

    #[ORM\Column]
    private int $campaignId;

    #[ORM\Column]
    private int $reach;

    #[ORM\Column]
    private int $results;

    #[ORM\Column]
    private int $impressions;

    #[ORM\Column]
    private int $costPerResult;

    #[ORM\Column]
    private int $cost;

    #[ORM\Column]
    private string $currency;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $importedAt;

    public function __construct(
        int $userId,
        int $adSetId,
        int $campaignId,
        int $reach,
        int $results,
        int $impressions,
        int $costPerResult,
        int $cost,
        string $currency,
        \DateTimeInterface $date,
        \DateTimeInterface $importedAt
    ) {
        $this->userId = $userId;
        $this->adSetId = $adSetId;
        $this->campaignId = $campaignId;
        $this->reach = $reach;
        $this->results = $results;
        $this->impressions = $impressions;
        $this->costPerResult = $costPerResult;
        $this->cost = $cost;
        $this->currency = $currency;
        $this->date = $date;
        $this->importedAt = $importedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAdSetId(): int
    {
        return $this->adSetId;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    public function getReach(): int
    {
        return $this->reach;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getResults(): int
    {
        return $this->results;
    }

    public function getCostPerResult(): int
    {
        return $this->costPerResult;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getCurrency(): Currency
    {
        return Currency::of($this->currency);
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getImportedAt(): \DateTimeInterface
    {
        return $this->importedAt;
    }
}
