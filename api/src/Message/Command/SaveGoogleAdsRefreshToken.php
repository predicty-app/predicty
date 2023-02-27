<?php

declare(strict_types=1);

namespace App\Message\Command;

class SaveGoogleAdsRefreshToken
{
    public int $userId;
    public string $refreshToken;

    public function __construct(int $userId, string $refreshToken)
    {
        $this->userId = $userId;
        $this->refreshToken = $refreshToken;
    }
}
