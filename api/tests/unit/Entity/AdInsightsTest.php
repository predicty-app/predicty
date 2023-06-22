<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AdInsights;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Entity\AdInsights
 */
class AdInsightsTest extends TestCase
{
    public function test_get_currency(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('PLN', $adStats->getCurrency()->getCurrencyCode());
    }

    public function test_get_cost_per_result(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(14.56, $adStats->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_get_amount_spent(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('PLN', $adStats->getCurrency()->getCurrencyCode());
        $this->assertSame(203.84, $adStats->getAmountSpent()->getAmount()->toFloat());
    }

    public function test_get_cost_per_click(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(25.68, $adStats->getCostPerClick()->getAmount()->toFloat());
    }

    public function test_get_cost_per_mil(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(12.34, $adStats->getCostPerMil()->getAmount()->toFloat());
    }

    public function test_get_date(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('2021-01-01', $adStats->getDate()->format('Y-m-d'));
    }

    public function test_get_id(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('01H1VEC8SYM3K6TSDAPFN25XZV', $adStats->getId()->toBase32());
    }

    public function test_get_user_id(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('01H1VECDYVB5BRQVPTSVJP3BZA', $adStats->getUserId()->toBase32());
    }

    public function test_get_account_id(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('01H1VECKZXA5X21VBCMWMFM0T5', $adStats->getAccountId()->toBase32());
    }

    public function test_get_ad_id(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame('01H1VH1W58G5MW6C2487ZPAB5F', $adStats->getAdId()->toBase32());
    }

    public function test_get_impressions(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(5000, $adStats->getImpressions());
    }

    public function test_get_clicks(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(500, $adStats->getClicks());
    }

    public function test_get_conversions(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(14, $adStats->getConversions());
    }

    public function test_get_leads(): void
    {
        $adStats = $this->createAdStats();
        $this->assertSame(14, $adStats->getLeads());
    }

    private function createAdStats(): AdInsights
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VECKZXA5X21VBCMWMFM0T5');
        $adId = Ulid::fromString('01H1VH1W58G5MW6C2487ZPAB5F');

        $date = DateHelper::fromString('2021-01-01', 'Y-m-d');
        $cpr = MoneyHelper::amount(14.567, Currency::of('PLN'));
        $cpc = MoneyHelper::amount(25.689, Currency::of('PLN'));
        $spent = MoneyHelper::amount(203.84, Currency::of('PLN'));
        $cpm = MoneyHelper::amount(12.345, Currency::of('PLN'));

        return new AdInsights(
            id: $id,
            userId: $userId,
            accountId: $accountId,
            adId: $adId,
            amountSpent: $spent,
            date: $date,
            conversions: 14,
            clicks: 500,
            impressions: 5000,
            leads: 14,
            costPerClick: $cpc,
            costPerResult: $cpr,
            costPerMil: $cpm
        );
    }
}
