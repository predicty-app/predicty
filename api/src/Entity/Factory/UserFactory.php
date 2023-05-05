<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\DoctrineUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFactory
{
    public function __construct(
        private UuidFactory $uuidFactory,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(string $email, ?string $password = null): DoctrineUser
    {
        $user = new DoctrineUser($email);
        $user->setUuid($this->uuidFactory->create());

        if ($password !== null) {
            $password = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($password);
        }

        return $user;
    }
}
