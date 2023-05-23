<?php

declare(strict_types=1);

namespace App\Service\Google;

use RuntimeException;

/**
 * Provides Google OAuth credentials for current app.
 */
class GoogleOAuthClientCredentials
{
    private string $clientId;
    private string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getClientId(): string
    {
        if ($this->clientId === '') {
            throw new RuntimeException('ClientId cannot be empty. Maybe you forgot to set the GOOGLE_ADS_OAUTH_CLIENT_ID env variable?');
        }

        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        if ($this->clientSecret === '') {
            throw new RuntimeException('Client secret cannot be empty. Maybe you forgot to set the GOOGLE_ADS_OAUTH_CLIENT_SECRET env variable?');
        }

        return $this->clientSecret;
    }
}
