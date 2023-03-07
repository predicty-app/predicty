<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdSet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class AdsFixtures extends Fixture implements DependentFixtureInterface
{
    public const AD_1 = 'AD1';
    public const AD_2 = 'AD2';
    public const AD_3 = 'AD3';
    public const AD_4 = 'AD4';
    public const AD_5 = 'AD5';
    public const AD_6 = 'AD6';

    public function __construct(private ClockInterface $clock)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var AdSet $adSet1 */
        $adSet1 = $this->getReference(AdSetsFixtures::ADSET_1);

        /** @var AdSet $adSet2 */
        $adSet2 = $this->getReference(AdSetsFixtures::ADSET_2);

        /** @var AdSet $adSet3 */
        $adSet3 = $this->getReference(AdSetsFixtures::ADSET_3);

        /** @var AdSet $adSet4 */
        $adSet4 = $this->getReference(AdSetsFixtures::ADSET_4);

        $data = [
            ['ad-external-id-1', $adSet1, 'Dummy Ad 1', self::AD_1],
            ['ad-external-id-2', $adSet1, 'Dummy Ad 2', self::AD_2],
            ['ad-external-id-3', $adSet1, 'Dummy Ad 3', self::AD_3],
            ['ad-external-id-4', $adSet2, 'Dummy Ad 4', self::AD_4],
            ['ad-external-id-5', $adSet2, 'Dummy Ad 5', self::AD_5],
            ['ad-external-id-6', $adSet3, 'Dummy Ad 6', self::AD_6],
        ];

        foreach ($data as $row) {
            $entity = new Ad(
                userId: $row[1]->getUserId(),
                externalId: $row[0],
                adSetId: $row[1]->getId(),
                campaignId: $row[1]->getCampaignId(),
                name: $row[2],
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now()
            );

            $manager->persist($entity);
            $this->addReference($row[3], $entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AdSetsFixtures::class,
        ];
    }
}
