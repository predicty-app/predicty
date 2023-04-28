<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DailyRevenue;
use App\Entity\User;
use Brick\Money\Currency;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DailyRevenueFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            // day, reve, aov
            ['2023-01-02', 139190, 2454],
            ['2023-01-03', 142740, 2627],
            ['2023-01-04', 163050, 707],
            ['2023-01-05', 131560, 1210],
            ['2023-01-06', 133070, 2279],
            ['2023-01-07', 153820, 667],
            ['2023-01-08', 170330, 558],
            ['2023-01-09', 135080, 2663],
            ['2023-01-10', 164230, 1324],
            ['2023-01-11', 147840, 458],
            ['2023-01-12', 149050, 1487],
            ['2023-01-13', 127290, 1766],
            ['2023-01-14', 161360, 876],
            ['2023-01-15', 136690, 907],
            ['2023-01-16', 143920, 2012],
            ['2023-01-17', 139780, 821],
            ['2023-01-18', 166960, 1812],
        ];

        $user = $this->getReference(UserFixture::JOHN);
        assert($user instanceof User);

        $currency = Currency::of('PLN');
        foreach ($data as $row) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $row[0]);
            assert($date instanceof DateTimeImmutable);
            $entity = new DailyRevenue(
                userId: $user->getId(),
                date: $date,
                revenue: Money::ofMinor($row[1], $currency),
                averageOrderValue: Money::ofMinor($row[2], $currency),
            );
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
