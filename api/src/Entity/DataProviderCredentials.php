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
    /** @phpstan-ignore-next-line */
    private DataProvider $dataProvider;

    #[ORM\Column(nullable: true)]
    /** @phpstan-ignore-next-line */
    private array $credentials = [];

    public function __construct(int $userId, DataProvider $dataProvider, array $credentials = [])
    {
        $this->userId = $userId;
        $this->dataProvider = $dataProvider;
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

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }
}
