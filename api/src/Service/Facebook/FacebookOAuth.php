<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use League\OAuth2\Client\Provider\Facebook;
use RuntimeException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookOAuth
{
    private Facebook $provider;

    public function __construct(FacebookOAuthClientCredentials $credentials, UrlGeneratorInterface $urlGenerator)
    {
        $this->provider = new Facebook([
            'clientId' => $credentials->getClientId(),
            'clientSecret' => $credentials->getClientSecret(),
            'redirectUri' => $urlGenerator->generate('facebook_oauth_callback', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'graphApiVersion' => 'v17.0',
        ]);
    }

    public function getRequestId(): string
    {
        // @phpstan-ignore-next-line
        return $this->provider->getState() ?? throw new RuntimeException('Request ID was not found. Make sure to call getAuthenticationUrl() first.');
    }

    public function getAuthenticationUrl(array $scopes): string
    {
        return $this->provider->getAuthorizationUrl([
            'scope' => $scopes,
        ]);
    }

    public function getAccessToken(string $code): string
    {
        return $this->provider->getAccessToken('authorization_code', ['code' => $code])->getToken();
    }

    public function getLongLivedAccessToken(string $accessToken): string
    {
        return $this->provider->getLongLivedAccessToken($accessToken)->getToken();
    }
}
