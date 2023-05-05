<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\UserProvider;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\UserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultUserProvider implements UserProvider
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        return $this->userRepository->findByUsername($identifier) ?? throw new UserNotFoundException('User not found');
    }
}
