<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimeDurationTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['name'])]
#[ORM\Index(fields: ['startedAt'])]
class AdCollection implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimeDurationTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private array $adsIds = [];

    /**
     * @param array<Ulid> $adsIds
     */
    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        string $name,
        array $adsIds = [],
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $endedAt = null,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
        $this->accountId = $accountId;

        $this->addAdsIds($adsIds);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<Ulid>
     */
    public function getAdsIds(): array
    {
        return array_map(fn (string $id) => Ulid::fromString($id), $this->adsIds);
    }

    public function addAdsIds(array $adsIds): void
    {
        $adsIds = array_map(fn (Ulid $id) => (string) $id, $adsIds);
        $this->adsIds = array_unique(array_merge($this->adsIds, $adsIds));
        $this->changedAt = Clock::now();
    }

    public function removeAdsIds(array $adsIds): void
    {
        $adsIds = array_map(fn (Ulid $id) => (string) $id, $adsIds);
        $this->adsIds = array_values(array_diff($this->adsIds, $adsIds));
        $this->changedAt = Clock::now();
    }
}
