<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Ad;
use App\Entity\AdStats;
use App\Repository\AdStatsRepository;
use Brick\Money\Money;
use DateTimeImmutable;

class AdStatsFactory
{
    public function __construct(private AdStatsRepository $adStatsRepository)
    {
    }

    public function make(
        Ad $ad,
        DateTimeImmutable $date,
        int $results,
        Money $costPerResult,
        Money $amountSpent
    ): AdStats {
        // todo: validate currencies
        $adStats = $this->adStatsRepository->findByAdIdAndDay($ad->getId(), $date);
        if ($adStats === null) {
            $adStats = new AdStats(
                userId: $ad->getUserId(),
                adId: $ad->getId(),
                results: $results,
                costPerResult: $costPerResult,
                amountSpent: $amountSpent,
                date: $date,
            );
            $this->adStatsRepository->save($adStats);
        }

        return $adStats;
    }
}
