<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AdStats;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Entity\AdStats
 */
class AdStatsTest extends TestCase
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

    private function createAdStats(): AdStats
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VECKZXA5X21VBCMWMFM0T5');
        $adId = Ulid::fromString('01H1VH1W58G5MW6C2487ZPAB5F');

        $date = DateHelper::fromString('2021-01-01', 'Y-m-d');
        $cpr = MoneyHelper::amount(14.567, Currency::of('PLN'));
        $spent = MoneyHelper::amount(203.84, Currency::of('PLN'));

        return new AdStats(
            id: $id,
            userId: $userId,
            accountId: $accountId,
            adId: $adId,
            results: 14,
            costPerResult: $cpr,
            amountSpent: $spent,
            date: $date
        );
    }
}
