<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Uid\Ulid;

class RequestPasscode
{
    public function __construct(public Ulid $userId)
    {
    }
}
