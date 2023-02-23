<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', enumType: DataProviderType::class)]
#[ORM\DiscriminatorMap([
    'GOOGLE_ADS' => GoogleAdsCredentials::class,
    'FACEBOOK_ADS' => FacebookAdsCredentials::class,
])]
abstract class DataProviderCredentials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    private DataProviderType $type;

    #[ORM\Column(nullable: true)]
    private array $credentials = [];

    public function __construct(int $userId, DataProviderType $type, array $credentials)
    {
        $this->userId = $userId;
        $this->type = $type;
        $this->credentials = $credentials;
    }

    public function getId(): int
    {
        assert($this->id !== null);

        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getType(): DataProviderType
    {
        return $this->type;
    }

    public function getCredentials(): array
    {
        return $this->credentials;
    }

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }
}
