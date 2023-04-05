<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class RequestPasswordResetToken
{
    #[Assert\Email]
    #[Assert\NotBlank(message: 'You must provide a username')]
    public string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }
}
