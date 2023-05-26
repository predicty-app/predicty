<?php

declare(strict_types=1);

namespace App\Service\Facebook;

class FacebookOAuthClientCredentials
{
    public function __construct(private string $clientId, private string $clientSecret)
    {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}
