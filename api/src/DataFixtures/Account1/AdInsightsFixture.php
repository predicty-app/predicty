<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\Ad;
use App\Entity\AdInsights;
use App\Service\Util\DateHelper;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class AdInsightsFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $ad1 = $this->getReference(AdFixture::AD_1, Ad::class);
        $ad2 = $this->getReference(AdFixture::AD_2, Ad::class);
        $ad4 = $this->getReference(AdFixture::AD_4, Ad::class);

        $data = [
            // ad, day, results, cpr (as int, in the smallest currency unit), clicks, impressions
            [$ad1, '2023-01-02', 24, 15, 4365, 34520],
            [$ad1, '2023-01-03', 5, 20, 4571, 34520],
            [$ad1, '2023-01-04', 0, 25, 4658, 54778],
            [$ad1, '2023-01-05', 0, 30, 4688, 46578],
            [$ad1, '2023-01-06', 1, 46, 4258, 34520],
            [$ad1, '2023-01-07', 1, 50, 4658, 4573],
            [$ad1, '2023-01-08', 5, 65, 4658, 234],
            [$ad1, '2023-01-09', 3, 15, 4658, 24657],
            [$ad1, '2023-01-10', 20, 10, 4658, 34520],
            [$ad1, '2023-01-11', 30, 2, 4658, 34520],
            [$ad1, '2023-01-12', 15, 1, 4658, 34520],
            [$ad1, '2023-01-13', 1, 50, 4658, 34520],
            [$ad1, '2023-01-14', 5, 65, 4658, 34520],
            [$ad1, '2023-01-15', 3, 15, 4658, 34520],
            [$ad1, '2023-01-16', 20, 10, 4658, 34520],
            [$ad1, '2023-01-17', 30, 2, 4658, 34520],
            [$ad1, '2023-01-18', 15, 1, 4658, 34520],
            [$ad2, '2023-01-02', 24, 15, 3578, 34520],
            [$ad2, '2023-01-03', 5, 20, 3578, 34520],
            [$ad2, '2023-01-04', 0, 25, 3578, 34520],
            [$ad2, '2023-01-05', 0, 30, 3578, 34520],
            [$ad2, '2023-01-06', 1, 46, 3578, 34520],
            [$ad2, '2023-01-07', 1, 50, 3578, 34520],
            [$ad2, '2023-01-08', 5, 65, 3578, 34520],
            [$ad2, '2023-01-09', 3, 15, 3578, 34520],
            [$ad4, '2023-01-10', 20, 10, 7683, 34520],
            [$ad4, '2023-01-11', 30, 2, 7683, 34520],
            [$ad4, '2023-01-12', 15, 1, 7683, 34520],
            [$ad4, '2023-01-13', 1, 50, 7683, 34520],
            [$ad4, '2023-01-14', 5, 65, 7683, 34520],
            [$ad4, '2023-01-15', 3, 15, 7683, 34520],
            [$ad4, '2023-01-16', 20, 10, 7683, 34520],
            [$ad4, '2023-01-17', 30, 2, 7683, 34520],
            [$ad4, '2023-01-18', 15, 1, 7683, 34520],
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

            $date = DateHelper::fromString($row[1], 'Y-m-d');

            if ($amountSpent->isZero()) {
                $costPerClick = Money::zero($currency);
            } else {
                $costPerClick = $amountSpent->dividedBy($row[4], RoundingMode::HALF_UP);
            }

            if ($amountSpent->isZero()) {
                $costPerMil = Money::zero($currency);
            } else {
                $costPerMil = $amountSpent->dividedBy($row[5], RoundingMode::HALF_UP)->multipliedBy(1000);
            }

            $entity = new AdInsights(
                id: new Ulid(),
                userId: $row[0]->getUserId(),
                accountId: $row[0]->getAccountId(),
                adId: $row[0]->getId(),
                amountSpent: $amountSpent,
                date: $date,
                conversions: $row[2],
                clicks: $row[4],
                impressions: $row[5],
                leads: 0,
                costPerClick: $costPerClick,
                costPerResult: $costPerResult,
                costPerMil: $costPerMil,
            );

            $manager->persist($entity);
            ++$id;
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
