<?php

declare(strict_types=1);

namespace App\Service\Facebook\CsvImporter;

class CsvHeadersValidator
{
    private static array $headers = [
        'Day',
        'Ad name',
        'Campaign name',
        'Result type',
        'Results',
        'Cost per result',
        'Amount spent (PLN)',
        'Ad ID',
        'Ad set ID',
        'Campaign ID',
        'Reporting starts',
        'Reporting ends',
    ];

    public static function validate(array $headers): void
    {
        $headers = array_flip($headers);
        foreach (self::$headers as $name) {
            if (!isset($headers[$name])) {
                throw new \RuntimeException(sprintf('Invalid CSV file: missing the required "%s" column', $name));
            }
        }
    }
}
