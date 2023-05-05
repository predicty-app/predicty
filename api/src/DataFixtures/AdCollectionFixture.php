<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class AdCollectionFixture extends Fixture implements DependentFixtureInterface
{
    public const AD_COLLECTION_1 = 'AD_COLLECTION_1';
    public const AD_COLLECTION_2 = 'AD_COLLECTION_2';
    public const EMPTY_COLLECTION = 'AD_COLLECTION_3';

    public function __construct(private ClockInterface $clock)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $ad1 = $this->getReference(AdFixture::AD_1, Ad::class);
        $ad2 = $this->getReference(AdFixture::AD_2, Ad::class);
        $ad3 = $this->getReference(AdFixture::AD_3, Ad::class);
        $ad4 = $this->getReference(AdFixture::AD_4, Ad::class);

        // all adds belong to the same user
        $userId = $ad1->getUserId();
        $data = [
            ['Red Collection', $userId, [$ad1->getId(), $ad2->getId()], self::AD_COLLECTION_1],
            ['Green Collection', $userId, [$ad2->getId(), $ad3->getId(), $ad4->getId()], self::AD_COLLECTION_2],
            ['Blue Collection', $userId, [], self::EMPTY_COLLECTION],
        ];

        foreach ($data as $row) {
            $entity = new AdCollection(
                userId: $row[1],
                name: $row[0],
                adsIds: $row[2],
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now(),
            );

            $manager->persist($entity);
            $this->addReference($row[3], $entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdFixture::class,
        ];
    }
}
