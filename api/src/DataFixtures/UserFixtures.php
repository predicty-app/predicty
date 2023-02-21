<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\User\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFixtures extends Fixture
{
    public const JOHN = 'john.doe@example.com';

    public function __construct(private UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userFactory->create('john.doe@example.com', '123456');

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::JOHN, $user);
    }
}
