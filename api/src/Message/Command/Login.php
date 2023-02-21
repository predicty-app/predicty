<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Message\SynchronousCommand;

class Login implements SynchronousCommand
{
    public readonly string $username;
    public readonly string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
