<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFactory
{
    public function __construct(private UuidFactory $uuidFactory, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(string $email, ?string $password = null): User
    {
        $user = new User($email);
        $user->setUuid($this->uuidFactory->create());

        if ($password !== null) {
            $password = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($password);
        }

        return $user;
    }
}
