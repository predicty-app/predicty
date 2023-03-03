<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CampaignRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $importedAt;

    public function __construct(int $userId, string $name, \DateTimeInterface $importedAt)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->importedAt = $importedAt;
    }

    public function getId(): int
    {
        if($this->id === null) {
            throw new \RuntimeException('Entity was not saved yet, therefore it does not have its id');
        }

        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
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

    public function getImportedAt(): \DateTimeInterface
    {
        return $this->importedAt;
    }
}
