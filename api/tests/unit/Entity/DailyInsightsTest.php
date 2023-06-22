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
            date: new DateTimeImmutable('2020-01-01'),
            results: 2,
            clicks: 150,
            impressions: 5689,
            leads: 10,
            averageOrderValue: Money::ofMinor(2500, 'EUR'),
            amountSpent: Money::ofMinor(3478, 'EUR'),
            revenue: Money::ofMinor(4940, 'EUR')
        );

        $this->assertEquals(new DateTimeImmutable('2020-01-01'), $insights->getDate());
        $this->assertEquals(2, $insights->getResults());
        $this->assertEquals(150, $insights->getClicks());
        $this->assertEquals(5689, $insights->getImpressions());
        $this->assertEquals(10, $insights->getLeads());
        $this->assertEquals(25., $insights->getAverageOrderValue()->getAmount()->toFloat());
        $this->assertEquals(34.78, $insights->getAmountSpent()->getAmount()->toFloat());
        $this->assertEquals(49.4, $insights->getRevenue()->getAmount()->toFloat());
        $this->assertEquals(17.39, $insights->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_from_scalar(): void
    {
        $insights = DailyInsights::fromScalar('2020-01-01', 2, 150, 5689, 10, 2500, 3478, 4940, 'EUR');

        $this->assertEquals(new DateTimeImmutable('2020-01-01'), $insights->getDate());
        $this->assertEquals(2, $insights->getResults());
        $this->assertEquals(150, $insights->getClicks());
        $this->assertEquals(5689, $insights->getImpressions());
        $this->assertEquals(10, $insights->getLeads());
        $this->assertEquals(25., $insights->getAverageOrderValue()->getAmount()->toFloat());
        $this->assertEquals(34.78, $insights->getAmountSpent()->getAmount()->toFloat());
        $this->assertEquals(49.4, $insights->getRevenue()->getAmount()->toFloat());
        $this->assertEquals(17.39, $insights->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_get_cost_per_result_with_zero_results_or_zero_amount_spent(): void
    {
        $insights = DailyInsights::fromScalar('2020-01-01', 0, 0, 0, 0, 2500, 3478, 4940, 'EUR');
        $this->assertEquals(0, $insights->getCostPerResult()->getAmount()->toFloat());

        $insights = DailyInsights::fromScalar('2020-01-01', 10, 0, 0, 0, 2500, 0, 4940, 'EUR');
        $this->assertEquals(0, $insights->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_get_cost_per_click(): void
    {
        $insights = DailyInsights::fromScalar(
            date: '2020-01-01',
            results: 10,
            clicks: 1500,
            impressions: 0,
            leads: 0,
            averageOrderValue: 2500,
            amountSpent: 5500, // 55.00 EUR
            revenue: 4940,
            currency: 'EUR'
        );

        $this->assertEquals(0.04, $insights->getCostPerClick()->getAmount()->toFloat());
    }

    public function test_get_cost_per_mil(): void
    {
        $insights = DailyInsights::fromScalar(
            date: '2020-01-01',
            results: 10,
            clicks: 1500,
            impressions: 5689,
            leads: 0,
            averageOrderValue: 2500,
            amountSpent: 5500, // 55.00 EUR
            revenue: 4940,
            currency: 'EUR'
        );

        $this->assertEquals(10, $insights->getCostPerMil()->getAmount()->toFloat());
    }
}
