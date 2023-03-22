<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
