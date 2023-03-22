<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class Login
{
    #[Assert\Email]
    #[Assert\NotBlank(message: 'You must provide a username')]
    public readonly string $username;

    #[Assert\NotBlank(message: 'Passcode cannot be empty')]
    #[Assert\Length(min: 6)]
    public readonly string $passcode;

    public function __construct(string $username, string $passcode)
    {
        $this->username = $username;
        $this->passcode = $passcode;
    }
}
