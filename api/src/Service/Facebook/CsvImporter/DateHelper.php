<?php

declare(strict_types=1);

namespace App\Service\Facebook\CsvImporter;

use DateTimeImmutable;
use RuntimeException;

class DateHelper
{
    public static function fromString(string $date, string $format = 'Y-m-d'): DateTimeImmutable
    {
        $datetime = DateTimeImmutable::createFromFormat($format, $date);

        if ($datetime === false) {
            throw new RuntimeException(sprintf('Invalid date format given: "%s" (expecting %s)', $date, $format));
        }

        return $datetime;
    }
}
