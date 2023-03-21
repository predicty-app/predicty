<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
class DataProvider
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?int $userId;

    #[ORM\Column(enumType: DataProviderType::class)]
    private DataProviderType $type;

    public function __construct(DataProviderType $type, string $name = '', ?int $userId = null)
    {
        $this->userId = $userId;
        $this->type = $type;
        $this->name = $name;
        $this->createdAt = $createdAt ?? Clock::now();
        $this->changedAt = $changedAt ?? Clock::now();
    }

    public function getName(): string
    {
        if ($this->name !== '') {
            return $this->name;
        }

        return $this->type->getName();
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getType(): DataProviderType
    {
        return $this->type;
    }

    /**
     * @return array<FileImportType>
     */
    public function getFileImportTypes(): array
    {
        return match ($this->type) {
            DataProviderType::FACEBOOK_ADS => [
                FileImportType::FACEBOOK_CSV,
            ],
            DataProviderType::OTHER => [
                FileImportType::SIMPLIFIED_CSV,
            ],
            default => []
        };
    }
}
