<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\Ad;
use App\Entity\AdCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

/**
 * All ad collections belong to the same user and account.
 */
class AdCollectionFixture extends Fixture implements DependentFixtureInterface
{
    public const AD_COLLECTION_1 = '01H1S4Z6W4K72G8QAXHT4D0G6C';
    public const AD_COLLECTION_2 = '01H1S4ZCVAFC9N8PZZKEVYCQE9';
    public const EMPTY_COLLECTION = '01H1S4ZJ9APK18FG0J8AJ6M7N2';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Ad $ad1 */
        $ad1 = $this->getReference(AdFixture::AD_1, Ad::class);
        $ad2 = $this->getReference(AdFixture::AD_2, Ad::class);
        $ad3 = $this->getReference(AdFixture::AD_3, Ad::class);
        $ad4 = $this->getReference(AdFixture::AD_4, Ad::class);

        // all adds belong to the same user
        $data = [
            self::AD_COLLECTION_1 => new AdCollection(Ulid::fromString(self::AD_COLLECTION_1), $ad1->getUserId(), $ad1->getAccountId(), 'Red Collection', [$ad1->getId(), $ad2->getId()]),
            self::AD_COLLECTION_2 => new AdCollection(Ulid::fromString(self::AD_COLLECTION_2), $ad1->getUserId(), $ad1->getAccountId(), 'Green Collection', [$ad2->getId(), $ad3->getId(), $ad4->getId()]),
            self::EMPTY_COLLECTION => new AdCollection(Ulid::fromString(self::EMPTY_COLLECTION), $ad1->getUserId(), $ad1->getAccountId(), 'Blue Collection', []),
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
            AdFixture::class,
        ];
    }
}
