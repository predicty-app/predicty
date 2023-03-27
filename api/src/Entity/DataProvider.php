<?php

declare(strict_types=1);

namespace App\Entity;

enum DataProvider: string
{
    case GOOGLE_ADS = 'GOOGLE_ADS';
    case FACEBOOK_ADS = 'FACEBOOK_ADS';
    case GOOGLE_ANALYTICS = 'GOOGLE_ANALYTICS';
    case TIK_TOK = 'TIK_TOK';
    case OTHER = 'OTHER';

    public function getCode(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return match ($this) {
            self::GOOGLE_ADS => 'Google Ads',
            self::FACEBOOK_ADS => 'Facebook Ads (Meta)',
            self::GOOGLE_ANALYTICS => 'Google Analytics',
            self::TIK_TOK => 'TikTok',
            self::OTHER => 'Other',
        };
    }

    /**
     * @return array<FileImportType>
     */
    public function getFileImportTypes(): array
    {
        return match ($this) {
            self::FACEBOOK_ADS => [
                FileImportType::FACEBOOK_CSV,
            ],
            self::GOOGLE_ANALYTICS => [
                FileImportType::GOOGLE_ANALYTICS_REVENUE,
            ],
            default => []
        };
    }
}
