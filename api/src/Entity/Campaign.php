<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['externalId'])]
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

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    public function __construct(
        string $externalId,
        int $userId,
        string $name,
        DataProvider $dataProvider = DataProvider::OTHER,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $changedAt = null
    ) {
        $this->externalId = $externalId;
        $this->userId = $userId;
        $this->name = $name;
        $this->dataProvider = $dataProvider;
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

    public function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }
}
