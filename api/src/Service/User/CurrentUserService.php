<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserOwnedEntity;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CurrentUserService
{
    public function __construct(private Security $security)
    {
    }

    public function getId(): int
    {
        return $this->getUser()->getId();
    }

    public function getUser(): User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }

        throw new AuthenticationException('There is no logged in user available');
    }

    public function isAnonymous(): bool
    {
        return $this->security->getUser() === null;
    }

    public function isAnOwnerOf(UserOwnedEntity $entity): bool
    {
        return $entity->getUserId() === $this->getId();
    }
}
