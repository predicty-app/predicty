<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface PasswordAuthenticatedUser extends PasswordAuthenticatedUserInterface
{
    public function setPassword(string $password): void;
}
