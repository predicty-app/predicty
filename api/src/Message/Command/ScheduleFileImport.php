<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProvider;
use App\Entity\FileImportType;
use Symfony\Component\Uid\Ulid;

class ScheduleFileImport
{
    public function __construct(
        public Ulid $userId,
        public Ulid $accountId,
        public DataProvider $dataProvider,
        public FileImportType $fileImportType,
        public string $filename,
        public array $metadata = [],
    ) {
    }
}
