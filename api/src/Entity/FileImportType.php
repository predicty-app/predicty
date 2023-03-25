<?php

declare(strict_types=1);

namespace App\Entity;

use RuntimeException;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
    case FACEBOOK_SIMPLIFIED_CSV = 'FACEBOOK_SIMPLIFIED_CSV';
    case GOOGLE_ANALYTICS_REVENUE = 'GOOGLE_ANALYTICS_REVENUE';
    case OTHER = 'OTHER';

    public function getDataProvider(): DataProvider
    {
        return match ($this) {
            self::FACEBOOK_CSV, self::FACEBOOK_SIMPLIFIED_CSV => DataProvider::FACEBOOK_ADS,
            self::GOOGLE_ANALYTICS_REVENUE => DataProvider::GOOGLE_ANALYTICS,
            default => throw new RuntimeException('FileImportType does not have a data provider')
        };
    }
}
