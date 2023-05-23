<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Google\Ads\GoogleAdsCredentials;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GoogleAdsConnectedAccount extends ConnectedAccount implements GoogleAdsCredentials
{
    private const REFRESH_TOKEN_KEY = 'refresh_token';
    private const CUSTOMER_ID = 'customer_id';

    public function update(string $refreshToken, string $customerId): void
    {
        $this->updateCredentials([
            self::REFRESH_TOKEN_KEY => $refreshToken,
            self::CUSTOMER_ID => $customerId,
        ]);
    }

    public function getRefreshToken(): string
    {
        return $this->getCredentialsKey(self::REFRESH_TOKEN_KEY);
    }

    public function getCustomerId(): string
    {
        return $this->getCredentialsKey(self::CUSTOMER_ID);
    }

    public function getDataProvider(): DataProvider
    {
        return DataProvider::GOOGLE_ADS;
    }
}
