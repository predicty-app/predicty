<?php

declare(strict_types=1);

namespace App\Message\Event;

class UserResetPassword
{
    public function __construct(public int $userId)
    {
    }
}
