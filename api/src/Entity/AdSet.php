<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AdSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private int $campaignId;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $importedAt;

    public function __construct(int $userId, int $campaignId, string $name, \DateTimeInterface $importedAt)
    {
        $this->userId = $userId;
        $this->campaignId = $campaignId;
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

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getImportedAt(): \DateTimeInterface
    {
        return $this->importedAt;
    }
}
