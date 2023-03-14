<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FileImport extends Import
{
    #[ORM\Column(length: 255)]
    private string $filename;

    public function __construct(
        int $userId,
        string $filename,
        DataProviderType $type,
        DateTimeImmutable $createdAt
    ) {
        parent::__construct($userId, $type, $createdAt);
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
