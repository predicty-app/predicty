<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserService
{
    public function __construct(private Security $security)
    {
    }

    public function getCurrentUser(): User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }

        throw new \RuntimeException('There is no logged in user available');
    }
}
