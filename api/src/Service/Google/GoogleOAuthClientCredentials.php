<?php

declare(strict_types=1);

namespace App\Service\Google;

class GoogleOAuthClientCredentials
{
    public readonly string $clientId;
    public readonly string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        if ($clientId === '') {
            throw new \RuntimeException('ClientId cannot be empty. Maybe you forgot to set the GOOGLE_ADS_OAUTH_CLIENT_ID env variable?');
        }

        if ($clientSecret === '') {
            throw new \RuntimeException('Client secret cannot be empty. Maybe you forgot to set the GOOGLE_ADS_OAUTH_CLIENT_SECRET env variable?');
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }
}
