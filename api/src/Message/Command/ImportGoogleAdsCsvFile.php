<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Message\AsyncMessage;

class ImportGoogleAdsCsvFile implements AsyncMessage
{
    public function __construct(public int $importId, public int $userId, public string $filename)
    {
    }
}
