<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private UuidFactory $uuidFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User('john.doe@example.com');
        $password = $this->passwordHasher->hashPassword($user, '123456');
        $user->setPassword($password);
        $user->setUuid($this->uuidFactory->create());

        $manager->persist($user);
        $manager->flush();
    }
}
