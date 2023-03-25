<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Ad;
use App\Entity\AdStats;
use App\Repository\AdStatsRepository;
use Brick\Money\Money;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class AdStatsFactory
{
    public function __construct(private AdStatsRepository $adStatsRepository, private ClockInterface $clock)
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
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now(),
            );
            $this->adStatsRepository->save($adStats);
        }

        return $adStats;
    }
}
