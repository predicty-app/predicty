<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * All ads belong to John, to hist first account.
 */
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
        /** @var AdSet $adSet1 */
        $adSet1 = $this->getReference(AdSetsFixture::ADSET_1, AdSet::class);
        $adSet2 = $this->getReference(AdSetsFixture::ADSET_2, AdSet::class);
        $adSet3 = $this->getReference(AdSetsFixture::ADSET_3, AdSet::class);

        $data = [
            self::AD_1 => new Ad($adSet1->getUserId(), $adSet1->getAccountId(), 'ad-external-id-1', $adSet1->getCampaignId(), 'Dummy Ad 1', $adSet1->getId(), null, DateHelper::fromString('2023-01-02 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-18 23:59:59', 'Y-m-d H:i:s')),
            self::AD_2 => new Ad($adSet1->getUserId(), $adSet1->getAccountId(), 'ad-external-id-2', $adSet1->getCampaignId(), 'Dummy Ad 2', $adSet1->getId(), null, DateHelper::fromString('2023-01-03 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-09 23:59:59', 'Y-m-d H:i:s')),
            self::AD_3 => new Ad($adSet1->getUserId(), $adSet1->getAccountId(), 'ad-external-id-3', $adSet1->getCampaignId(), 'Dummy Ad 3', $adSet1->getId()),
            self::AD_4 => new Ad($adSet2->getUserId(), $adSet2->getAccountId(), 'ad-external-id-4', $adSet2->getCampaignId(), 'Dummy Ad 4', $adSet2->getId(), null, DateHelper::fromString('2023-01-10 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-18 23:59:59', 'Y-m-d H:i:s')),
            self::AD_5 => new Ad($adSet2->getUserId(), $adSet2->getAccountId(), 'ad-external-id-5', $adSet2->getCampaignId(), 'Dummy Ad 5', $adSet2->getId()),
            self::AD_6 => new Ad($adSet3->getUserId(), $adSet3->getAccountId(), 'ad-external-id-6', $adSet3->getCampaignId(), 'Dummy Ad 6', $adSet3->getId()),
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
            AdSetsFixture::class,
        ];
    }
}
