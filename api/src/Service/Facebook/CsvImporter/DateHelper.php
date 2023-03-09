<?php

declare(strict_types=1);

namespace App\Service\Facebook\CsvImporter;

use DateTimeImmutable;
use RuntimeException;

class DateHelper
{
    private const FORMAT = 'Y-m-d';

    public static function fromString(string $date): DateTimeImmutable
    {
        $datetime = DateTimeImmutable::createFromFormat(self::FORMAT, $date);

        if ($datetime === false) {
            throw new RuntimeException(sprintf('Invalid date format given: "%s" (expecting %s)', $date, self::FORMAT));
        }

        return $datetime;
    }
}
