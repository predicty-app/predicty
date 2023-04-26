<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;

// @todo change to ConnectedAccount
#[ORM\Entity]
class DataProviderCredentials
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private DataProvider $dataProvider;

    #[ORM\Column(nullable: true)]
    private array $credentials = [];

    public function __construct(int $userId, DataProvider $dataProvider, array $credentials = [])
    {
        $this->userId = $userId;
        $this->dataProvider = $dataProvider;
        $this->credentials = $credentials;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
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
}
