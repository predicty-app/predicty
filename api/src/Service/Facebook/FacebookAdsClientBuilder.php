<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use FacebookAds\Api as FacebookApi;
use FacebookAds\Http\Client as FacebookHttpClient;
use FacebookAds\Session as FacebookHttpClientSession;
use RuntimeException;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FacebookAdsClientBuilder
{
    private FacebookHttpClient $client;
    private static bool $wasAlreadyBuilt = false;

    public function __construct(
        private FacebookOAuthClientCredentials $clientCredentials,
        private FacebookUserCredentialsProvider $userCredentialsProvider,
        private HttpClientInterface $httpClient
    ) {
        $this->client = new FacebookHttpClient();
    }

    public function build(Ulid $connectedAccountId): FacebookAdsClient
    {
        if (self::$wasAlreadyBuilt) {
            // facebook library is not stateless
            throw new RuntimeException('FacebookAdsClientBuilder can only be used once per request.');
        }

        $userCredentials = $this->userCredentialsProvider->getCredentials($connectedAccountId);

        $session = new FacebookHttpClientSession(
            $this->clientCredentials->getClientId(),
            $this->clientCredentials->getClientSecret(),
            $userCredentials->getAccessToken()
        );

        $adapter = new SymfonyFacebookBridgeAdapter($this->client, $this->httpClient);
        $this->client->setAdapter($adapter);

        $api = new FacebookApi($this->client, $session);
        $api::setInstance($api);

        return new FacebookAdsClient(new FacebookApi($this->client, $session), $userCredentials->getAdAccountId());
    }
}
