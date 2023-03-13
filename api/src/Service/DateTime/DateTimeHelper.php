<?php

declare(strict_types=1);

namespace App\Service\DateTime;

use DateTimeImmutable;
use RuntimeException;

class DateTimeHelper
{
    public static function createFromFormat(string $format, string $date): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat($format, $date);

        if (!$date instanceof DateTimeImmutable) {
            throw new RuntimeException(sprintf('Invalid date given "%s" for given date format: %s', $date, $format));
        }

        return $date;
    }
}
