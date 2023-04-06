<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\WithdrawImport;

use App\DataFixtures\UserFixtures;
use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Entity\User;
use App\Service\Clock\Clock;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WithdrawImportFixture extends Fixture implements DependentFixtureInterface
{
    public const IMPORT1 = 'IMPORT1';

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::JOHN, User::class);

        $import = new FileImport(
            userId: $user->getId(),
            filename: 'test.csv',
            fileImportType: FileImportType::OTHER_SIMPLIFIED_CSV
        );

        $manager->persist($import);
        $manager->flush();

        $campaign = new Campaign(
            externalId: '123',
            userId: $user->getId(),
            name: 'Test Campaign',
            importId: $import->getId()
        );

        $manager->persist($campaign);
        $manager->flush();

        $adSet = new AdSet(
            externalId: '123',
            userId: $user->getId(),
            campaignId: $campaign->getId(),
            name: 'Test AdSet',
            importId: $import->getId()
        );

        $manager->persist($adSet);
        $manager->flush();

        $ad = new Ad(
            userId: $user->getId(),
            externalId: '123',
            campaignId: $campaign->getId(),
            name: 'Test Ad',
            adSetId: $adSet->getId(),
            importId: $import->getId()
        );

        $manager->persist($ad);
        $manager->flush();

        $adStats = new AdStats(
            userId: $user->getId(),
            adId: $ad->getId(),
            results: 100,
            costPerResult: Money::of(10, 'USD'),
            amountSpent: Money::of(1000, 'USD'),
            date: Clock::now()
        );

        $adStats->setImportId($import->getId());

        $manager->persist($adStats);
        $manager->flush();

        $this->addReference(self::IMPORT1, $import);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
