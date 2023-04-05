<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{
    #[Assert\NotBlank(message: 'Token cannot be blank')]
    public string $token;

    #[Assert\NotCompromisedPassword(skipOnError: true)]
    #[Assert\NotBlank(message: 'New password should not be blank')]
    public string $password;

    public function __construct(string $token, string $password)
    {
        $this->token = $token;
        $this->password = $password;
    }
}
