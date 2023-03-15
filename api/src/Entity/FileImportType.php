<?php

declare(strict_types=1);

namespace App\Entity;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';

    public function getDataProviderType(): DataProviderType
    {
        return DataProviderType::FACEBOOK_ADS;
    }
}
