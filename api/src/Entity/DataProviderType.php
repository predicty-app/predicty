<?php

declare(strict_types=1);

namespace App\Entity;

enum DataProviderType: string
{
    case GOOGLE_ADS = 'GOOGLE_ADS';
    case FACEBOOK_ADS = 'FACEBOOK_ADS';
    case GOOGLE_ANALYTICS = 'GOOGLE_ANALYTICS';
    case TIK_TOK = 'TIK_TOK';
    case OTHER = 'OTHER';

    public function getName(): string
    {
        return match ($this) {
            DataProviderType::GOOGLE_ADS => 'Google Ads',
            DataProviderType::FACEBOOK_ADS => 'Facebook Ads (Meta)',
            DataProviderType::GOOGLE_ANALYTICS => 'Google Analytics',
            DataProviderType::TIK_TOK => 'TikTok',
            DataProviderType::OTHER => 'Other',
        };
    }
}
