<?php

declare(strict_types=1);

namespace App\Service\Google;

use Google\Ads\GoogleAds\Lib\Configuration;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;

class GoogleAdsClientFactory
{
    public function __construct()
    {
    }

    public function build(): GoogleAdsClient
    {
        $configuration = new Configuration([
            'GOOGLE_ADS' => [
                'developerToken' => '',
                'loginCustomerId' => '',
            ],
            'OAUTH' => [
                'clientId' => '',
                'clientSecret' => '',
                'refreshToken' => '',
            ],
        ]);

        $oAuth2Credential = (new OAuth2TokenBuilder())->from($configuration)->build();

        $client = (new GoogleAdsClientBuilder())
            ->from($configuration)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        return new GoogleAdsClient($client);
    }
}
