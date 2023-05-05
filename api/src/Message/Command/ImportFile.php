<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\FileImportType;

class ImportFile
{
    public function __construct(
        public int $importId,
        public int $userId,
        public int $accountId,
        public FileImportType $fileImportType,
        public string $filename,
        public array $metadata = []
    ) {
    }
}
