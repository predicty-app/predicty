<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const JOHN = 'john.doe@example.com';
    public const JANE = 'jane.doe@example.com';

    public function __construct(private UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = $this->userFactory->create(self::JOHN, '123456');
        $user2 = $this->userFactory->create(self::JANE, '123456');

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();

        $this->addReference(self::JOHN, $user1);
        $this->addReference(self::JANE, $user2);
    }
}
