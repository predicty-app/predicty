<?php

declare(strict_types=1);

namespace App\Message;

class Register implements Command
{
    public readonly string $email;
    public readonly string $password;

    public function __construct(string $email, string $password = '')
    {
        $this->email = $email;
        $this->password = $password;
    }
}
