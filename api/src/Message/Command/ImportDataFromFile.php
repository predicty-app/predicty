<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\FileImportType;

class ImportDataFromFile
{
    public function __construct(public int $userId, public FileImportType $type, public string $filename)
    {
    }
}
