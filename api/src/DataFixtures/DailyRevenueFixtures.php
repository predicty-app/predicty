<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DailyRevenue;
use Brick\Money\Currency;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DailyRevenueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            // day, reve, aov
            ['2023-01-02', 13919, 2454],
            ['2023-01-03', 14274, 2627],
            ['2023-01-04', 16305, 707],
            ['2023-01-05', 13156, 1210],
            ['2023-01-06', 13307, 2279],
            ['2023-01-07', 15382, 667],
            ['2023-01-08', 17033, 558],
            ['2023-01-09', 13508, 2663],
            ['2023-01-10', 16423, 1324],
            ['2023-01-11', 14784, 458],
            ['2023-01-12', 14905, 1487],
            ['2023-01-13', 12729, 1766],
            ['2023-01-14', 16136, 876],
            ['2023-01-15', 13669, 907],
            ['2023-01-16', 14392, 2012],
            ['2023-01-17', 13978, 821],
            ['2023-01-18', 16696, 1812],
        ];

        $currency = Currency::of('PLN');
        foreach ($data as $row) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $row[0]);
            assert($date instanceof DateTimeImmutable);
            $entity = new DailyRevenue(
                date: $date,
                revenue: Money::ofMinor($row[1], $currency),
                averageOrderValue: Money::ofMinor($row[2], $currency),
            );
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
