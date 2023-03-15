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

    #[ORM\Column]
    private FileImportType $fileImportType;

    public function __construct(
        int $userId,
        string $filename,
        FileImportType $fileImportType,
        ?DateTimeImmutable $createdAt = null
    ) {
        parent::__construct($userId, $fileImportType->getDataProviderType(), $createdAt);
        $this->filename = $filename;
        $this->fileImportType = $fileImportType;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFileImportType(): FileImportType
    {
        return $this->fileImportType;
    }
}
