<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Message\SynchronousCommand;
use App\Validator\EmailUnique;
use Symfony\Component\Validator\Constraints as Assert;

class Register implements SynchronousCommand
{
    #[EmailUnique]
    #[Assert\Email(message: 'Invalid email')]
    #[Assert\NotBlank(message: 'You must provide an email')]
    public readonly string $email;
    public readonly ?string $password;

    public function __construct(string $email, string $password = null)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
