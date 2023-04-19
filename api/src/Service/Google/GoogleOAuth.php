<?php

declare(strict_types=1);

namespace App\Service\Google;

use Google\Auth\OAuth2;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class GoogleOAuth
{
    private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_CREDENTIAL_URI = 'https://oauth2.googleapis.com/token';
    private OAuth2 $oauth;
    private ?string $stateToken = null;

    public function __construct(
        private GoogleOAuthClientCredentials $appCredentials,
        private ClientInterface $httpClient
    ) {
        $this->oauth = new OAuth2([
            'clientId' => $this->appCredentials->getClientId(),
            'clientSecret' => $this->appCredentials->getClientSecret(),
            'authorizationUri' => self::AUTHORIZATION_URI,
            'tokenCredentialUri' => self::TOKEN_CREDENTIAL_URI,
            'state' => $this->getRequestId(),
        ]);
    }

    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->oauth->setRedirectUri($redirectUrl);
    }

    public function getRequestId(): string
    {
        if ($this->stateToken === null) {
            $this->stateToken = sha1(openssl_random_pseudo_bytes(1024));
        }

        return $this->stateToken;
    }

    public function getAuthenticationUrl(string $redirectUrl, array $scope): string
    {
        $this->oauth->setRedirectUri($redirectUrl);
        $this->oauth->setScope($scope);

        return (string) $this->oauth->buildFullAuthorizationUri(['access_type' => 'offline']);
    }

    public function fetchRefreshToken(string $authCode): string
    {
        $this->oauth->setCode($authCode);
        $httpHandler = fn (RequestInterface $request) => $this->httpClient->sendRequest($request);
        $authToken = $this->oauth->fetchAuthToken($httpHandler);

        return (string) $authToken['refresh_token'];
    }

    public function fetchAccessToken(string $refreshToken): void
    {
        $this->oauth->updateToken(['refresh_token' => $refreshToken]);
    }

    public function getOAuth(): OAuth2
    {
        return $this->oauth;
    }
}
