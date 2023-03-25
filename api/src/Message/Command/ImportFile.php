<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProvider;
use App\Entity\FileImportType;

class ImportFile
{
    public function __construct(
        public int $userId,
        public DataProvider $dataProvider,
        public FileImportType $fileImportType,
        public string $filename,
        public ?string $campaignName = null,
    ) {
    }
}
