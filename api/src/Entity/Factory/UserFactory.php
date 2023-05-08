<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\User;
use Psr\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFactory
{
    public function __construct(
        private UuidFactory $uuidFactory,
        private UserPasswordHasherInterface $passwordHasher,
        private ClockInterface $clock
    ) {
    }

    public function create(string $email, ?string $password = null): User
    {
        $user = new User($email, $this->clock->now(), $this->clock->now());
        $user->setUuid($this->uuidFactory->create());

        if ($password !== null) {
            $password = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($password);
        }

        return $user;
    }
}
