<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'api' => ApiImport::class,
    'file' => FileImport::class,
])]
abstract class Import implements Ownable
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private ImportStatus $status;

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    #[ORM\Column(type: Types::TEXT)]
    private string $message = '';

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $result = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $completedAt;

    public function __construct(
        int $userId,
        DataProvider $dataProvider,
    ) {
        $this->userId = $userId;
        $this->status = ImportStatus::WAITING;
        $this->dataProvider = $dataProvider;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
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

    public function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }

    public function getResult(): ImportResult
    {
        return ImportResult::fromArray($this->result ?? []);
    }

    public function complete(ImportResult $importResult): void
    {
        assert($this->status === ImportStatus::IN_PROGRESS, 'Import can only be completed when it was previously in progress');
        $this->status = ImportStatus::COMPLETE;
        $this->result = $importResult->toArray();
        $this->completedAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function start(): void
    {
        assert($this->status === ImportStatus::WAITING, 'Import can only be started when it is waiting');
        $this->status = ImportStatus::IN_PROGRESS;
        $this->startedAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function fail(string $message): void
    {
        $this->message = $message;
        $this->completedAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->status = ImportStatus::FAILED;
    }

    public function withdraw(): void
    {
        $this->status = ImportStatus::WITHDRAWN;
        $this->changedAt = Clock::now();
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->userId === $user->getId();
    }
}
