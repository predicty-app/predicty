<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProvider;
use App\Entity\FileImportType;

class ScheduleFileImport
{
    public function __construct(
        public int $userId,
        public int $accountId,
        public DataProvider $dataProvider,
        public FileImportType $fileImportType,
        public string $filename,
        public array $metadata = [],
    ) {
    }
}
