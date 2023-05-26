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
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn('data_provider')]
#[ORM\DiscriminatorMap([
    'GOOGLE_ANALYTICS' => GoogleAnalyticsConnectedAccount::class,
    'GOOGLE_ADS' => GoogleAdsConnectedAccount::class,
    'FACEBOOK_ADS' => FacebookAdsConnectedAccount::class,
])]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
abstract class ConnectedAccount implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(nullable: true)]
    private array $credentials;

    #[ORM\Column]
    private bool $isEnabled;

    public function __construct(
        Ulid $id,
        Ulid $accountId,
        Ulid $userId,
        bool $isEnabled = true
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->isEnabled = $isEnabled;
        $this->credentials = [];
    }

    public function getUserId(): Ulid
    {
        return $this->userId;
    }

    abstract public function getDataProvider(): DataProvider;

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    protected function getCredentialsKey(string $key): string
    {
        return $this->credentials[$key] ?? '';
    }

    protected function updateCredentials(array $credentials): void
    {
        $this->credentials = $credentials;
        $this->changedAt = Clock::now();
    }
}
