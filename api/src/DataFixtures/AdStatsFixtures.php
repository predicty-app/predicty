<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdStats;
use App\Service\DateTime\DateTimeHelper;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdStatsFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Ad $ad1 */
        $ad1 = $this->getReference(AdFixtures::AD_1);

        /** @var Ad $ad2 */
        $ad2 = $this->getReference(AdFixtures::AD_2);

        /** @var Ad $ad4 */
        $ad4 = $this->getReference(AdFixtures::AD_4);

        $data = [
            // ad, day, results, cpr (as int, in the smallest currency unit)
            [$ad1, '2023-01-02', 24, 15],
            [$ad1, '2023-01-03', 5, 20],
            [$ad1, '2023-01-04', 0, 25],
            [$ad1, '2023-01-05', 0, 30],
            [$ad1, '2023-01-06', 1, 46],
            [$ad1, '2023-01-07', 1, 50],
            [$ad1, '2023-01-08', 5, 65],
            [$ad1, '2023-01-09', 3, 15],
            [$ad1, '2023-01-10', 20, 10],
            [$ad1, '2023-01-11', 30, 2],
            [$ad1, '2023-01-12', 15, 1],
            [$ad1, '2023-01-13', 1, 50],
            [$ad1, '2023-01-14', 5, 65],
            [$ad1, '2023-01-15', 3, 15],
            [$ad1, '2023-01-16', 20, 10],
            [$ad1, '2023-01-17', 30, 2],
            [$ad1, '2023-01-18', 15, 1],

            [$ad2, '2023-01-02', 24, 15],
            [$ad2, '2023-01-03', 5, 20],
            [$ad2, '2023-01-04', 0, 25],
            [$ad2, '2023-01-05', 0, 30],
            [$ad2, '2023-01-06', 1, 46],
            [$ad2, '2023-01-07', 1, 50],
            [$ad2, '2023-01-08', 5, 65],
            [$ad2, '2023-01-09', 3, 15],
            [$ad2, '2023-01-10', 20, 10],
            [$ad2, '2023-01-11', 30, 2],
            [$ad2, '2023-01-12', 15, 1],
            [$ad2, '2023-01-13', 1, 50],
            [$ad2, '2023-01-14', 5, 65],
            [$ad2, '2023-01-15', 3, 15],
            [$ad2, '2023-01-16', 20, 10],
            [$ad2, '2023-01-17', 30, 2],
            [$ad2, '2023-01-18', 15, 1],

            [$ad4, '2023-01-02', 24, 15],
            [$ad4, '2023-01-03', 5, 20],
            [$ad4, '2023-01-04', 0, 25],
            [$ad4, '2023-01-05', 0, 30],
            [$ad4, '2023-01-06', 1, 46],
            [$ad4, '2023-01-07', 1, 50],
            [$ad4, '2023-01-08', 5, 65],
            [$ad4, '2023-01-09', 3, 15],
            [$ad4, '2023-01-10', 20, 10],
            [$ad4, '2023-01-11', 30, 2],
            [$ad4, '2023-01-12', 15, 1],
            [$ad4, '2023-01-13', 1, 50],
            [$ad4, '2023-01-14', 5, 65],
            [$ad4, '2023-01-15', 3, 15],
            [$ad4, '2023-01-16', 20, 10],
            [$ad4, '2023-01-17', 30, 2],
            [$ad4, '2023-01-18', 15, 1],
        ];

        $id = 1;
        foreach ($data as $row) {
            $currency = Currency::of('PLN');

            if ($row[2] === 0) {
                $costPerResult = Money::zero($currency);
                $amountSpent = Money::zero($currency);
            } else {
                $costPerResult = Money::ofMinor($row[3], $currency);
                $amountSpent = $costPerResult->multipliedBy($row[2]);
            }

            $date = DateTimeHelper::createFromFormat('Y-m-d', $row[1]);

            $entity = new AdStats(
                userId: $row[0]->getUserId(),
                adId: $row[0]->getId(),
                results: $row[2],
                costPerResult: $costPerResult,
                amountSpent: $amountSpent,
                date: $date
            );

            $manager->persist($entity);
            ++$id;
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
