<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\DailyInsights;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\DailyInsights
 */
class DailyInsightsTest extends TestCase
{
    public function test_get_results(): void
    {
        $insights = new DailyInsights(
            new DateTimeImmutable('2020-01-01'),
            2,
            Money::ofMinor(2500, 'EUR'),
            Money::ofMinor(3478, 'EUR'),
            Money::ofMinor(4940, 'EUR')
        );

        $this->assertEquals(new DateTimeImmutable('2020-01-01'), $insights->getDate());
        $this->assertEquals(2, $insights->getResults());
        $this->assertEquals(25., $insights->getAverageOrderValue()->getAmount()->toFloat());
        $this->assertEquals(34.78, $insights->getAmountSpent()->getAmount()->toFloat());
        $this->assertEquals(49.4, $insights->getRevenue()->getAmount()->toFloat());
        $this->assertEquals(17.39, $insights->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_from_scalar(): void
    {
        $insights = DailyInsights::fromScalar('2020-01-01', 2, 2500, 3478, 4940, 'EUR');

        $this->assertEquals(new DateTimeImmutable('2020-01-01'), $insights->getDate());
        $this->assertEquals(2, $insights->getResults());
        $this->assertEquals(25., $insights->getAverageOrderValue()->getAmount()->toFloat());
        $this->assertEquals(34.78, $insights->getAmountSpent()->getAmount()->toFloat());
        $this->assertEquals(49.4, $insights->getRevenue()->getAmount()->toFloat());
        $this->assertEquals(17.39, $insights->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_get_cost_per_result_with_zero_results_or_zero_amount_spent(): void
    {
        $insights = DailyInsights::fromScalar('2020-01-01', 0, 2500, 3478, 4940, 'EUR');
        $this->assertEquals(0, $insights->getCostPerResult()->getAmount()->toFloat());

        $insights = DailyInsights::fromScalar('2020-01-01', 10, 2500, 0, 4940, 'EUR');
        $this->assertEquals(0, $insights->getCostPerResult()->getAmount()->toFloat());
    }
}
