<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdFixture extends Fixture implements DependentFixtureInterface
{
    public const AD_1 = 'AD1';
    public const AD_2 = 'AD2';
    public const AD_3 = 'AD3';
    public const AD_4 = 'AD4';
    public const AD_5 = 'AD5';
    public const AD_6 = 'AD6';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adSet1 = $this->getReference(AdSetsFixture::ADSET_1, AdSet::class);
        $adSet2 = $this->getReference(AdSetsFixture::ADSET_2, AdSet::class);
        $adSet3 = $this->getReference(AdSetsFixture::ADSET_3, AdSet::class);
        $adSet4 = $this->getReference(AdSetsFixture::ADSET_4, AdSet::class);

        $data = [
            ['ad-external-id-1', $adSet1, 'Dummy Ad 1', self::AD_1, '2023-01-02 00:00:00', '2023-01-18 23:59:59'],
            ['ad-external-id-2', $adSet1, 'Dummy Ad 2', self::AD_2, '2023-01-03 00:00:00', '2023-01-09 23:59:59'],
            ['ad-external-id-3', $adSet1, 'Dummy Ad 3', self::AD_3],
            ['ad-external-id-4', $adSet2, 'Dummy Ad 4', self::AD_4, '2023-01-10 00:00:00', '2023-01-18 23:59:59'],
            ['ad-external-id-5', $adSet2, 'Dummy Ad 5', self::AD_5],
            ['ad-external-id-6', $adSet3, 'Dummy Ad 6', self::AD_6],
        ];

        foreach ($data as $row) {
            $entity = new Ad(
                userId: $row[1]->getUserId(),
                externalId: $row[0],
                campaignId: $row[1]->getCampaignId(),
                name: $row[2],
                adSetId: $row[1]->getId(),
                startedAt: isset($row[4]) ? DateHelper::fromString($row[4], 'Y-m-d H:i:s') : null,
                endedAt: isset($row[5]) ? DateHelper::fromString($row[5], 'Y-m-d H:i:s') : null,
            );

            $manager->persist($entity);
            $this->addReference($row[3], $entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdSetsFixture::class,
        ];
    }
}
