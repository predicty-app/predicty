<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Util;

use App\Service\Util\MoneyHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\Util\MoneyHelper
 */
class MoneyHelperTest extends TestCase
{
    public function test_amount(): void
    {
        $money = MoneyHelper::amount(12.90, 'EUR');

        $this->assertEquals('EUR', $money->getCurrency()->getCurrencyCode());
        $this->assertEquals(12.9, $money->getAmount()->toFloat());
    }
}
