<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class LoginWithPassword
{
    #[Assert\Email]
    #[Assert\NotBlank(message: 'You must provide a username')]
    public readonly string $username;

    #[Assert\NotBlank(message: 'Password cannot be empty')]
    #[Assert\Length(min: 6)]
    public readonly string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
