<?php

declare(strict_types=1);

namespace App\Message\Event;

use Symfony\Component\Uid\Ulid;

class UserChangedPassword
{
    public function __construct(public Ulid $userId)
    {
    }
}
