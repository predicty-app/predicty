<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * All ad sets belong to John, to hist first account.
 */
class AdSetsFixture extends Fixture implements DependentFixtureInterface
{
    public const ADSET_1 = 'ADSET1';
    public const ADSET_2 = 'ADSET2';
    public const ADSET_3 = 'ADSET3';
    public const ADSET_4 = 'ADSET4';

    public function load(ObjectManager $manager): void
    {
        /** @var Campaign $campaign1 */
        $campaign1 = $this->getReference(CampaignFixture::CAMPAIGN_1, Campaign::class);
        $campaign2 = $this->getReference(CampaignFixture::CAMPAIGN_2, Campaign::class);
        $campaign3 = $this->getReference(CampaignFixture::CAMPAIGN_3, Campaign::class);

        $data = [
            self::ADSET_1 => new AdSet('adset-external-id-1', $campaign1->getUserId(), $campaign1->getAccountId(), $campaign1->getId(), 'Dummy AdSet 1', null, DateHelper::fromString('2023-01-02 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-18 23:59:59', 'Y-m-d H:i:s')),
            self::ADSET_2 => new AdSet('adset-external-id-2', $campaign1->getUserId(), $campaign1->getAccountId(), $campaign1->getId(), 'Dummy AdSet 2', null, DateHelper::fromString('2023-01-02 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-18 23:59:59', 'Y-m-d H:i:s')),
            self::ADSET_3 => new AdSet('adset-external-id-3', $campaign2->getUserId(), $campaign2->getAccountId(), $campaign2->getId(), 'Dummy AdSet 3'),
            self::ADSET_4 => new AdSet('adset-external-id-4', $campaign3->getUserId(), $campaign3->getAccountId(), $campaign3->getId(), 'Dummy AdSet 4'),
        ];

        foreach ($data as $reference => $entity) {
            $manager->persist($entity);
            $this->addReference($reference, $entity);
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
