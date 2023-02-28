<?php

declare(strict_types=1);

namespace App\Service\Google;

use Google\Auth\OAuth2;

class GoogleOAuth
{
    private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const SCOPE = 'https://www.googleapis.com/auth/adwords';
    private const TOKEN_CREDENTIAL_URI = 'https://oauth2.googleapis.com/token';
    private OAuth2 $oauth;
    private ?string $stateToken = null;

    public function __construct(private GoogleOAuthClientCredentials $appCredentials)
    {
        $this->oauth = new OAuth2([
            'clientId' => $this->appCredentials->clientId,
            'clientSecret' => $this->appCredentials->clientSecret,
            'authorizationUri' => self::AUTHORIZATION_URI,
            'tokenCredentialUri' => self::TOKEN_CREDENTIAL_URI,
            'scope' => self::SCOPE,
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

    public function getAuthenticationUrl(string $redirectUrl): string
    {
        $this->oauth->setRedirectUri($redirectUrl);

        return (string) $this->oauth->buildFullAuthorizationUri(['access_type' => 'offline']);
    }

    public function fetchRefreshToken(string $authCode): string
    {
        $this->oauth->setCode($authCode);
        $authToken = $this->oauth->fetchAuthToken();

        return (string) $authToken['refresh_token'];
    }
}
