<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Message\AsyncMessage;

class ImportFacebookCsvFile implements AsyncMessage
{
    public function __construct(public int $importId, public string $filename)
    {
    }
}
