<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DataProvider;
use App\Entity\DataProviderType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DataProviderFixtures extends Fixture
{
    public const GOOGLE_ADS = 'GOOGLE';
    public const FACEBOOK_ADS = 'FACEBOOK_ADS';
    public const GOOGLE_ANALYTICS = 'GOOGLE_ANALYTICS';
    public const TIK_TOK = 'TIK_TOK';

    public function load(ObjectManager $manager): void
    {
        $providers = [
            self::GOOGLE_ADS => new DataProvider(DataProviderType::GOOGLE_ADS),
            self::FACEBOOK_ADS => new DataProvider(DataProviderType::FACEBOOK_ADS),
            self::GOOGLE_ANALYTICS => new DataProvider(DataProviderType::GOOGLE_ANALYTICS),
            self::TIK_TOK => new DataProvider(DataProviderType::TIK_TOK),
        ];

        foreach ($providers as $ref => $provider) {
            $manager->persist($provider);
            $this->referenceRepository->addReference($ref, $provider);
        }

        $manager->flush();
    }
}
