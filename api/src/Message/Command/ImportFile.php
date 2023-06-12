<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\FileImportType;
use Symfony\Component\Uid\Ulid;

class ImportFile
{
    public function __construct(
        public Ulid $importId,
        public Ulid $userId,
        public Ulid $accountId,
        public FileImportType $fileImportType,
        public string $filename,
        public array $metadata = []
    ) {
    }
}
