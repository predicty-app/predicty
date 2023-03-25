<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FileImport extends Import
{
    #[ORM\Column(length: 255)]
    private string $filename;

    #[ORM\Column(options: ['default' => FileImportType::OTHER])]
    private FileImportType $fileImportType;

    public function __construct(
        int $userId,
        string $filename,
        DataProvider $dataProvider,
        FileImportType $fileImportType = FileImportType::OTHER,
    ) {
        parent::__construct($userId, $dataProvider);
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
