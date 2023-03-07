<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class CampaignsFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGN_1 = 'CAMPAIGN1';
    public const CAMPAIGN_2 = 'CAMPAIGN2';
    public const CAMPAIGN_3 = 'CAMPAIGN3';

    public function __construct(private ClockInterface $clock)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::JOHN);

        $campaigns = [
            [$user->getId(), 'Campaign 1', 'external-id-1', self::CAMPAIGN_1],
            [$user->getId(), 'Campaign 2', 'external-id-2', self::CAMPAIGN_2],
            [$user->getId(), 'Campaign 3', 'external-id-3', self::CAMPAIGN_3],
        ];

        foreach ($campaigns as $campaign) {
            $entity = new Campaign(
                externalId: $campaign[2],
                userId: $campaign[0],
                name: $campaign[1],
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now()
            );

            $manager->persist($entity);
            $this->addReference($campaign[3], $entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
