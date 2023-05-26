<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\ImportableTrait;
use App\Entity\Trait\TimeDurationTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\Index(fields: ['startedAt'])]
#[ORM\UniqueConstraint(fields: ['accountId', 'externalId'])]
class Campaign implements Importable, UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use ImportableTrait;
    use TimeDurationTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        string $externalId,
        string $name,
        DataProvider $dataProvider = DataProvider::OTHER,
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->externalId = $externalId;
        $this->name = $name;
        $this->dataProvider = $dataProvider;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
        $this->accountId = $accountId;
        $this->id = $id;
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
