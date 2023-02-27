<?php

declare(strict_types=1);

namespace App\Entity;

enum DataProviderType: string
{
    case GOOGLE_ADS = 'GOOGLE_ADS';
    case FACEBOOK_ADS = 'FACEBOOK_ADS';
    case GOOGLE_ANALYTICS = 'GOOGLE_ANALYTICS';
}
