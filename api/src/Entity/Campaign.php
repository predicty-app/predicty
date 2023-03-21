<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\Index(fields: ['dataProviderId'])]
#[ORM\UniqueConstraint(fields: ['userId', 'externalId'])]
class Campaign
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $dataProviderId;

    public function __construct(
        string $externalId,
        int $userId,
        string $name,
        int $dataProviderId,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $changedAt = null
    ) {
        $this->externalId = $externalId;
        $this->userId = $userId;
        $this->name = $name;
        $this->dataProviderId = $dataProviderId;
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getDataProviderId(): int
    {
        return $this->dataProviderId;
    }
}
