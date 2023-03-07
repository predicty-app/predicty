<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['adSetId'])]
#[ORM\Index(fields: ['campaignId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\UniqueConstraint(fields: ['userId', 'externalId'])]
class Ad
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private int $adSetId;

    #[ORM\Column]
    private int $campaignId;

    #[ORM\Column]
    private string $name;

    public function __construct(
        int $userId,
        string $externalId,
        int $adSetId,
        int $campaignId,
        string $name,
        \DateTimeInterface $createdAt,
        \DateTimeInterface $changedAt,
    ) {
        $this->userId = $userId;
        $this->externalId = $externalId;
        $this->adSetId = $adSetId;
        $this->campaignId = $campaignId;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->changedAt = $changedAt;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
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

    public function getName(): string
    {
        return $this->name;
    }
}
