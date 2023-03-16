<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class AdCollectionFixtures extends Fixture implements DependentFixtureInterface
{
    public const AD_COLLECTION_1 = 'AD_COLLECTION_1';
    public const AD_COLLECTION_2 = 'AD_COLLECTION_2';
    public const EMPTY_COLLECTION = 'AD_COLLECTION_3';

    public function __construct(private ClockInterface $clock)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Ad $ad1 */
        $ad1 = $this->getReference(AdFixtures::AD_1);

        /** @var Ad $ad2 */
        $ad2 = $this->getReference(AdFixtures::AD_2);

        /** @var Ad $ad3 */
        $ad3 = $this->getReference(AdFixtures::AD_3);

        // all adds belong to the same user
        $userId = $ad1->getId();
        $data = [
            ['Red Collection', $userId, [$ad1->getId(), $ad2->getId()], self::AD_COLLECTION_1],
            ['Green Collection', $userId, [$ad2->getId(), $ad3->getId()], self::AD_COLLECTION_2],
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
            AdFixtures::class,
        ];
    }
}
