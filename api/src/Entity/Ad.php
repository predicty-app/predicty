<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['adSetId'])]
#[ORM\Index(fields: ['campaignId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\Index(fields: ['startedAt'])]
#[ORM\UniqueConstraint(fields: ['userId', 'externalId'])]
class Ad implements Importable
{
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(nullable: true)]
    private ?int $adSetId = null;

    #[ORM\Column]
    private int $campaignId;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $endedAt;

    public function __construct(
        int $userId,
        string $externalId,
        int $campaignId,
        string $name,
        ?int $adSetId = null,
        ?int $importId = null,
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->userId = $userId;
        $this->externalId = $externalId;
        $this->adSetId = $adSetId;
        $this->campaignId = $campaignId;
        $this->name = $name;
        $this->importId = $importId;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAdSetId(): ?int
    {
        return $this->adSetId;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStartedAt(DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function setEndedAt(DateTimeImmutable $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getEndedAt(): ?DateTimeImmutable
    {
        return $this->endedAt;
    }
}
