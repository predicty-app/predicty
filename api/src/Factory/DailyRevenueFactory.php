<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\DailyRevenue;
use App\Repository\DailyRevenueRepository;
use Brick\Money\Money;
use DateTimeImmutable;

class DailyRevenueFactory
{
    public function __construct(private DailyRevenueRepository $dailyRevenueRepository)
    {
    }

    public function make(int $userId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue
    {
        $entity = $this->dailyRevenueRepository->findByDay($userId, $date);

        if ($entity === null) {
            $entity = new DailyRevenue($userId, $date, $revenue, $averageOrderValue);
            $this->dailyRevenueRepository->save($entity);
        }

        return $entity;
    }
}
