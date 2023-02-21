<?php

namespace App\Message;

class Login implements Command
{
    public readonly string $username;
    public readonly string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}