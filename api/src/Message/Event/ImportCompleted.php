<?php

declare(strict_types=1);

namespace App\Message\Event;

class ImportCompleted
{
    public function __construct(public int $importId)
    {
    }
}
