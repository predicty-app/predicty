<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['campaignId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\UniqueConstraint(fields: ['userId', 'externalId'])]
class AdSet implements Importable
{
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private int $campaignId;

    #[ORM\Column(length: 255)]
    private string $name;

    public function __construct(string $externalId, int $userId, int $campaignId, string $name)
    {
        $this->externalId = $externalId;
        $this->userId = $userId;
        $this->campaignId = $campaignId;
        $this->name = $name;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getUserId(): int
    {
        return $this->userId;
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
