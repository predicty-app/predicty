<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DataProvider;
use App\Entity\DataProviderType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CustomDataProviderFixtures extends Fixture implements DependentFixtureInterface
{
    public const CUSTOM_TV = 'CUSTOM_TV';
    public const CUSTOM_NEWSPAPER = 'CUSTOM_NEWSPAPER';

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::JOHN);

        $providers = [
            self::CUSTOM_TV => new DataProvider(DataProviderType::OTHER, 'TV Ads', $user->getId()),
            self::CUSTOM_NEWSPAPER => new DataProvider(DataProviderType::OTHER, 'Newspaper', $user->getId()),
        ];

        foreach ($providers as $ref => $provider) {
            $manager->persist($provider);
            $this->referenceRepository->addReference($ref, $provider);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DataProviderFixtures::class,
            UserFixtures::class,
        ];
    }
}
