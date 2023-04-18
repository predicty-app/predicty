<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\Index(fields: ['startedAt'])]
#[ORM\UniqueConstraint(fields: ['userId', 'externalId'])]
class Campaign implements Importable
{
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $endedAt;

    public function __construct(
        string $externalId,
        int $userId,
        string $name,
        DataProvider $dataProvider = DataProvider::OTHER,
        ?int $importId = null,
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->externalId = $externalId;
        $this->userId = $userId;
        $this->name = $name;
        $this->dataProvider = $dataProvider;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->importId = $importId;
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
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
}
