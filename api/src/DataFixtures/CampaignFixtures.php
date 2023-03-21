<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\DataProvider;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGN_1 = 'CAMPAIGN1';
    public const CAMPAIGN_2 = 'CAMPAIGN2';
    public const CAMPAIGN_3 = 'CAMPAIGN3';

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::JOHN);

        /** @var DataProvider[] $providers */
        $providers = [
            $this->getReference(DataProviderFixtures::FACEBOOK_ADS),
            $this->getReference(DataProviderFixtures::FACEBOOK_ADS),
            $this->getReference(CustomDataProviderFixtures::CUSTOM_TV),
        ];

        $campaigns = [
            [$user->getId(), 'Campaign 1', 'external-id-1', self::CAMPAIGN_1, $providers[0]->getId()],
            [$user->getId(), 'Campaign 2', 'external-id-2', self::CAMPAIGN_2, $providers[1]->getId()],
            [$user->getId(), 'Campaign 3', 'external-id-3', self::CAMPAIGN_3, $providers[2]->getId()],
        ];

        foreach ($campaigns as $campaign) {
            $entity = new Campaign(
                externalId: $campaign[2],
                userId: $campaign[0],
                name: $campaign[1],
                dataProviderId: $campaign[4],
            );

            $manager->persist($entity);
            $this->addReference($campaign[3], $entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DataProviderFixtures::class,
            CustomDataProviderFixtures::class,
        ];
    }
}
