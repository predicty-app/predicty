<?php

declare(strict_types=1);

namespace App\Service\Util;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;

class MoneyHelper
{
    public static function amount(string|float $amount, Currency|string $currency): Money
    {
        if (is_string($currency)) {
            $currency = Currency::of($currency);
        }

        return Money::of($amount, $currency, roundingMode: RoundingMode::DOWN);
    }
}
