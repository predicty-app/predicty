<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DataProviderCredentials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private int $dataProviderId;

    #[ORM\Column(nullable: true)]
    private array $credentials = [];

    public function __construct(int $userId, int $dataProviderId, array $credentials = [])
    {
        $this->userId = $userId;
        $this->dataProviderId = $dataProviderId;
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

    public function getDataProviderId(): int
    {
        return $this->dataProviderId;
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
