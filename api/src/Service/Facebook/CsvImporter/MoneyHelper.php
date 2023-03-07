<?php

declare(strict_types=1);

namespace App\Service\Facebook\CsvImporter;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;

class MoneyHelper
{
    public static function amount(string|float $amount, Currency $currency): Money
    {
        return Money::of($amount, $currency, roundingMode: RoundingMode::DOWN);
    }

    public function currency(string $currency): Currency
    {
        return Currency::of($currency);
    }
}
