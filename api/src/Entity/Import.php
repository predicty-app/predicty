<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'api' => ApiImport::class,
    'file' => FileImport::class,
])]
class Import
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private ImportStatus $status;

    #[ORM\Column(type: Types::TEXT)]
    private string $message = '';

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $completedAt;

    public function __construct(
        int $userId,
        DataProviderType $type,
        DateTimeImmutable $createdAt
    ) {
        $this->userId = $userId;
        $this->status = ImportStatus::WAITING;
        $this->createdAt = $createdAt;
        $this->changedAt = $createdAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getStatus(): ImportStatus
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function success(DateTimeImmutable $completedAt): void
    {
        $this->status = ImportStatus::COMPLETE;
        $this->completedAt = $completedAt;
        $this->changedAt = $completedAt;
    }

    public function start(DateTimeImmutable $startedAt): void
    {
        $this->status = ImportStatus::IN_PROGRESS;
        $this->startedAt = $startedAt;
        $this->changedAt = $startedAt;
    }

    public function fail(string $message, DateTimeInterface $failedAt): void
    {
        $this->message = $message;
        $this->completedAt = $failedAt;
        $this->changedAt = $failedAt;
        $this->status = ImportStatus::FAILED;
    }

    public function getStartedAt(): ?DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }
}
