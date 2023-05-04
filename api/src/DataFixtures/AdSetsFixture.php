<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdSetsFixture extends Fixture implements DependentFixtureInterface
{
    public const ADSET_1 = 'ADSET1';
    public const ADSET_2 = 'ADSET2';
    public const ADSET_3 = 'ADSET3';
    public const ADSET_4 = 'ADSET4';

    public function load(ObjectManager $manager): void
    {
        $campaign1 = $this->getReference(CampaignFixture::CAMPAIGN_1, Campaign::class);
        $campaign2 = $this->getReference(CampaignFixture::CAMPAIGN_2, Campaign::class);
        $campaign3 = $this->getReference(CampaignFixture::CAMPAIGN_3, Campaign::class);

        $userId = $campaign1->getUserId();

        $data = [
            // string $externalId, int $userId, int $campaignId, string $name
            [self::ADSET_1, 'adset-external-id-1', $userId, $campaign1->getId(), 'Dummy AdSet 1', '2023-01-02 00:00:00', '2023-01-18 23:59:59'],
            [self::ADSET_2, 'adset-external-id-2', $userId, $campaign1->getId(), 'Dummy AdSet 2', '2023-01-10 00:00:00', '2023-01-18 23:59:59'],
            [self::ADSET_3, 'adset-external-id-3', $userId, $campaign2->getId(), 'Dummy AdSet 3'],
            [self::ADSET_4, 'adset-external-id-4', $userId, $campaign3->getId(), 'Dummy AdSet 4'],
        ];

        foreach ($data as $row) {
            $entity = new AdSet(
                externalId: $row[1],
                userId: $row[2],
                campaignId: $row[3],
                name: $row[4],
                startedAt: isset($row[5]) ? DateHelper::fromString($row[5], 'Y-m-d H:i:s') : null,
                endedAt: isset($row[6]) ? DateHelper::fromString($row[6], 'Y-m-d H:i:s') : null,
            );

            $manager->persist($entity);
            $this->addReference($row[0], $entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampaignFixture::class,
        ];
    }
}
