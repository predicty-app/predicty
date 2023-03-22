<?php

declare(strict_types=1);

namespace App\Entity;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
    case OTHER = 'OTHER';

    public function getDataProvider(): DataProvider
    {
        return match ($this) {
            FileImportType::FACEBOOK_CSV => DataProvider::FACEBOOK_ADS,
            FileImportType::OTHER => DataProvider::OTHER,
        };
    }
}
