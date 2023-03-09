<?php

declare(strict_types=1);

namespace App\Message\Command;

class ImportDataFromFacebookCsvFile
{
    public function __construct(public int $importId, public string $filename)
    {
    }
}
