<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class Register
{
    #[Assert\Email(message: 'Invalid email')]
    #[Assert\NotBlank(message: 'You must provide an email')]
    public readonly string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
