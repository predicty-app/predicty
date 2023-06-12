<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimeDurationTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'api' => ApiImport::class,
    'file' => FileImport::class,
])]
abstract class Import implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimeDurationTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private ImportStatus $status;

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    #[ORM\Column(type: Types::TEXT)]
    private string $message = '';

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $result = [];

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        DataProvider $dataProvider,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->status = ImportStatus::WAITING;
        $this->dataProvider = $dataProvider;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->accountId = $accountId;
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
        $this->endedAt = Clock::now();
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
        $this->endedAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->status = ImportStatus::FAILED;
    }

    public function withdraw(): void
    {
        $this->status = ImportStatus::WITHDRAWN;
        $this->changedAt = Clock::now();
    }
}
