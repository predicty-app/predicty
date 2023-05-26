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
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['adSetId'])]
#[ORM\Index(fields: ['campaignId'])]
#[ORM\Index(fields: ['externalId'])]
#[ORM\Index(fields: ['startedAt'])]
#[ORM\UniqueConstraint(fields: ['accountId', 'externalId'])]
class Ad implements Importable, UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use ImportableTrait;
    use TimeDurationTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column(type: UlidType::NAME, unique: false, nullable: true)]
    private ?Ulid $adSetId;

    #[ORM\Column(type: UlidType::NAME, unique: false)]
    private Ulid $campaignId;

    #[ORM\Column(options: ['default' => DataProvider::OTHER])]
    private DataProvider $dataProvider;

    #[ORM\Column]
    private string $name;

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        Ulid $campaignId,
        string $externalId,
        string $name,
        ?Ulid $adSetId = null,
        DataProvider $dataProvider = DataProvider::OTHER,
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->externalId = $externalId;
        $this->adSetId = $adSetId;
        $this->campaignId = $campaignId;
        $this->name = $name;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
        $this->accountId = $accountId;
        $this->dataProvider = $dataProvider;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getAdSetId(): ?Ulid
    {
        return $this->adSetId;
    }

    public function getCampaignId(): Ulid
    {
        return $this->campaignId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }
}
