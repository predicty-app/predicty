<?php

declare(strict_types=1);

namespace App\Service\Google;

use App\Service\Param\ParamService;

class Credentials
{
    private const GOOGLE_ADS_DEVELOPER_TOKEN = 'GOOGLE_ADS_DEVELOPER_TOKEN';
    private const GOOGLE_ADS_CUSTOMER_ID = 'GOOGLE_ADS_CUSTOMER_ID';
    private const GOOGLE_ADS_OAUTH_CLIENT_ID = 'GOOGLE_ADS_OAUTH_CLIENT_ID';
    private const GOOGLE_ADS_OAUTH_CLIENT_SECRET = 'GOOGLE_ADS_OAUTH_CLIENT_SECRET';
    private const GOOGLE_ADS_OAUTH_REFRESH_TOKEN = 'GOOGLE_ADS_OAUTH_REFRESH_TOKEN';

    public function __construct(private ParamService $paramService)
    {
    }

    public function getGoogleAdsDeveloperToken(): string
    {
        return (string) $this->paramService->get(self::GOOGLE_ADS_DEVELOPER_TOKEN);
    }

    public function getGoogleAdsCustomerId(): int
    {
        return $this->paramService->getAsInt(self::GOOGLE_ADS_CUSTOMER_ID);
    }

    public function getGoogleAdsOauthClientId(): string
    {
        return (string) $this->paramService->get(self::GOOGLE_ADS_OAUTH_CLIENT_ID);
    }

    public function getGoogleAdsOauthClientSecret(): string
    {
        return (string) $this->paramService->get(self::GOOGLE_ADS_OAUTH_CLIENT_SECRET);
    }

    public function getGoogleAdsOauthRefreshToken(): string
    {
        return (string) $this->paramService->get(self::GOOGLE_ADS_OAUTH_REFRESH_TOKEN);
    }
}
