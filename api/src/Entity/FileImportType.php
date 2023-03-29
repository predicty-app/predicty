<?php

declare(strict_types=1);

namespace App\Entity;

use RuntimeException;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
    case OTHER_SIMPLIFIED_CSV = 'OTHER_SIMPLIFIED_CSV';
    case GOOGLE_ANALYTICS_REVENUE = 'GOOGLE_ANALYTICS_REVENUE';
    case OTHER = 'OTHER';

    public function getDataProvider(): DataProvider
    {
        return match ($this) {
            self::FACEBOOK_CSV => DataProvider::FACEBOOK_ADS,
            self::GOOGLE_ANALYTICS_REVENUE => DataProvider::GOOGLE_ANALYTICS,
            self::OTHER_SIMPLIFIED_CSV => DataProvider::OTHER,
            default => throw new RuntimeException('FileImportType does not have a data provider')
        };
    }
}
