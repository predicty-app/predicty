<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Service\Util\DateHelper;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

/**
 * All ads belong to John, to hist first account.
 */
class AdFixture extends Fixture implements DependentFixtureInterface
{
    public const AD_1 = '01H1PR91D4KRVM99EJSFJTYE4F';
    public const AD_2 = '01H1PR98Y5VH8GN8G4DFEQSXT5';
    public const AD_3 = '01H1PR9EE8Z32KJKEN6TRNNRMS';
    public const AD_4 = '01H1PR9KJB8KGT2XXARDA08H87';
    public const AD_5 = '01H1PR9RS97PJW8V1FCM0W60XY';
    public const AD_6 = '01H1PR9YDZCMA0TYFX9GVHVBRD';

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
            self::AD_1 => new Ad(Ulid::fromString(self::AD_1), $adSet1->getUserId(), $adSet1->getAccountId(), $adSet1->getCampaignId(), 'ad-external-id-1', 'Dummy Ad 1', $adSet1->getId(), null, $this->date('2023-01-02 00:00:00'), $this->date('2023-01-18 23:59:59')),
            self::AD_2 => new Ad(Ulid::fromString(self::AD_2), $adSet1->getUserId(), $adSet1->getAccountId(), $adSet1->getCampaignId(), 'ad-external-id-2', 'Dummy Ad 2', $adSet1->getId(), null, $this->date('2023-01-03 00:00:00'), $this->date('2023-01-09 23:59:59')),
            self::AD_3 => new Ad(Ulid::fromString(self::AD_3), $adSet1->getUserId(), $adSet1->getAccountId(), $adSet1->getCampaignId(), 'ad-external-id-3', 'Dummy Ad 3', $adSet1->getId()),
            self::AD_4 => new Ad(Ulid::fromString(self::AD_4), $adSet2->getUserId(), $adSet2->getAccountId(), $adSet2->getCampaignId(), 'ad-external-id-4', 'Dummy Ad 4', $adSet2->getId(), null, $this->date('2023-01-10 00:00:00'), $this->date('2023-01-18 23:59:59')),
            self::AD_5 => new Ad(Ulid::fromString(self::AD_5), $adSet2->getUserId(), $adSet2->getAccountId(), $adSet2->getCampaignId(), 'ad-external-id-5', 'Dummy Ad 5', $adSet2->getId()),
            self::AD_6 => new Ad(Ulid::fromString(self::AD_6), $adSet3->getUserId(), $adSet3->getAccountId(), $adSet3->getCampaignId(), 'ad-external-id-6', 'Dummy Ad 6', $adSet3->getId()),
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

    private function date(string $date): DateTimeImmutable
    {
        return DateHelper::fromString($date, 'Y-m-d H:i:s');
    }
}
