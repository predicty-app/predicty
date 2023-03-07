<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AdSet;
use App\Entity\Campaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class AdSetsFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADSET_1 = 'ADSET1';
    public const ADSET_2 = 'ADSET2';
    public const ADSET_3 = 'ADSET3';
    public const ADSET_4 = 'ADSET4';

    public function __construct(private ClockInterface $clock)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Campaign $campaign1 */
        $campaign1 = $this->getReference(CampaignsFixtures::CAMPAIGN_1);

        /** @var Campaign $campaign2 */
        $campaign2 = $this->getReference(CampaignsFixtures::CAMPAIGN_2);

        /** @var Campaign $campaign3 */
        $campaign3 = $this->getReference(CampaignsFixtures::CAMPAIGN_3);

        $data = [
            // string $externalId, int $userId, int $campaignId, string $name
            ['adset-external-id-1', $campaign1->getUserId(), $campaign1->getId(), 'Dummy AdSet 1', self::ADSET_1],
            ['adset-external-id-2', $campaign1->getUserId(), $campaign1->getId(), 'Dummy AdSet 2', self::ADSET_2],
            ['adset-external-id-3', $campaign2->getUserId(), $campaign2->getId(), 'Dummy AdSet 3', self::ADSET_3],
            ['adset-external-id-4', $campaign3->getUserId(), $campaign3->getId(), 'Dummy AdSet 4', self::ADSET_4],
        ];

        foreach ($data as $row) {
            $entity = new AdSet(
                externalId: $row[0],
                userId: $row[1],
                campaignId: $row[2],
                name: $row[3],
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now()
            );

            $manager->persist($entity);
            $this->addReference($row[4], $entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CampaignsFixtures::class,
        ];
    }
}
