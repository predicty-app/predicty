<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use App\Service\Google\GoogleOAuthClientCredentials;
use Google\Ads\GoogleAds\Lib\Configuration;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClientBuilder as InnerGoogleAdsClientBuilder;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Uid\Ulid;

class GoogleAdsClientBuilder
{
    public function __construct(
        private GoogleAdsCredentialsProvider $credentialsProvider,
        private GoogleOAuthClientCredentials $googleOAuthClientCredentials,
        private LoggerInterface $logger
    ) {
    }

    public function build(Ulid $connectedAccountId): GoogleAdsClient
    {
        $credentials = $this->credentialsProvider->getCredentials($connectedAccountId);

        $configuration = new Configuration([
            'GOOGLE_ADS' => [
                'developerToken' => $this->credentialsProvider->getDeveloperToken(),
                'loginCustomerId' => $credentials->getCustomerId(),
            ],
            'OAUTH2' => [
                'clientId' => $this->googleOAuthClientCredentials->getClientId(),
                'clientSecret' => $this->googleOAuthClientCredentials->getClientSecret(),
                'refreshToken' => $credentials->getRefreshToken(),
            ],
        ]);

        $oAuth2Credential = (new OAuth2TokenBuilder())->from($configuration)->build();

        $client = (new InnerGoogleAdsClientBuilder())
            ->from($configuration)
            ->withOAuth2Credential($oAuth2Credential)
            ->withLogger($this->logger)
            ->withLogLevel(LogLevel::DEBUG)
            ->build();

        return new GoogleAdsClient($credentials->getCustomerId(), $client);
    }
}
