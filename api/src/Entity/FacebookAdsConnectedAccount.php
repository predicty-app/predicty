<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Facebook\FacebookUserCredentials;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FacebookAdsConnectedAccount extends ConnectedAccount implements FacebookUserCredentials
{
    private const ACCESS_TOKEN = 'access_token';
    private const AD_ACCOUNT_ID = 'ad_account_id';

    public function update(string $accessToken, string $adAccountId): void
    {
        $this->updateCredentials([
            self::ACCESS_TOKEN => $accessToken,
            self::AD_ACCOUNT_ID => $adAccountId,
        ]);
    }

    public function getDataProvider(): DataProvider
    {
        return DataProvider::FACEBOOK_ADS;
    }

    public function getAccessToken(): string
    {
        return $this->getCredentialsKey(self::ACCESS_TOKEN);
    }

    public function getAdAccountId(): string
    {
        return $this->getCredentialsKey(self::AD_ACCOUNT_ID);
    }
}
