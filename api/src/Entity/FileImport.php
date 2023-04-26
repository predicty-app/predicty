<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

#[ORM\Entity]
class FileImport extends Import
{
    #[ORM\Column(length: 255)]
    private string $filename;

    #[ORM\Column(nullable: true, options: ['default' => null])]
    private ?FileImportType $fileImportType;

    public function __construct(
        int $userId,
        string $filename,
        FileImportType $fileImportType = FileImportType::OTHER,
    ) {
        parent::__construct($userId, $fileImportType->getDataProvider());
        $this->filename = $filename;
        $this->fileImportType = $fileImportType;
    }

    /**
     * Gets full filename like "uploads/file123.csv".
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Gets file basename like "file123.csv".
     */
    public function getBasename(): string
    {
        return pathinfo($this->filename, \PATHINFO_BASENAME);
    }

    public function getMimeType(): string
    {
        return match (pathinfo($this->filename, \PATHINFO_EXTENSION)) {
            'csv' => 'text/csv',
            default => throw new RuntimeException('Unknown file extension'),
        };
    }

    public function getFileImportType(): FileImportType
    {
        return $this->fileImportType ?? FileImportType::OTHER;
    }
}
