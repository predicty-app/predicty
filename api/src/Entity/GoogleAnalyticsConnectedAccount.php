<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Google\Analytics\GoogleAnalyticsCredentials;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GoogleAnalyticsConnectedAccount extends ConnectedAccount implements GoogleAnalyticsCredentials
{
    private const REFRESH_TOKEN_KEY = 'refresh_token';
    private const GA4_ID_KEY = 'ga4_id';

    public function update(string $refreshToken, string $ga4Id): void
    {
        $this->updateCredentials([
            self::REFRESH_TOKEN_KEY => $refreshToken,
            self::GA4_ID_KEY => $ga4Id,
        ]);
    }

    public function getRefreshToken(): string
    {
        return $this->getCredentialsKey(self::REFRESH_TOKEN_KEY);
    }

    public function getGA4Id(): string
    {
        return $this->getCredentialsKey(self::GA4_ID_KEY);
    }

    public function getDataProvider(): DataProvider
    {
        return DataProvider::GOOGLE_ANALYTICS;
    }
}
