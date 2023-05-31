<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
class ConnectedAccount implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private DataProvider $dataProvider;

    #[ORM\Column(nullable: true)]
    private array $credentials = [];

    #[ORM\Column]
    private bool $isEnabled;

    public function __construct(
        Ulid $id,
        Ulid $accountId,
        Ulid $userId,
        DataProvider $dataProvider,
        array $credentials = [],
        bool $isEnabled = true
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->dataProvider = $dataProvider;
        $this->credentials = $credentials;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->isEnabled = $isEnabled;
    }

    public function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }

    public function getCredentials(): array
    {
        return $this->credentials;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
