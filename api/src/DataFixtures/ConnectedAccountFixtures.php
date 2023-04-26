<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConnectedAccountFixtures extends Fixture implements DependentFixtureInterface
{
    public const CONNECTED_ACCOUNT_1 = 'CONNECTED_ACCOUNT_1';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::JOHN, User::class);
        $entity = new ConnectedAccount($user->getId(), DataProvider::GOOGLE_ANALYTICS, [], false);
        $manager->persist($entity);
        $this->addReference(self::CONNECTED_ACCOUNT_1, $entity);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
