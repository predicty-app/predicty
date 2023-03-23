<?php

declare(strict_types=1);

namespace App\Entity;

use RuntimeException;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
    case FACEBOOK_SIMPLIFIED_CSV = 'FACEBOOK_SIMPLIFIED_CSV';
    case OTHER = 'OTHER';

    public function getDataProvider(): DataProvider
    {
        return match ($this) {
            self::FACEBOOK_CSV, self::FACEBOOK_SIMPLIFIED_CSV => DataProvider::FACEBOOK_ADS,
            default => throw new RuntimeException('FileImportType does not have a data provider')
        };
    }
}
