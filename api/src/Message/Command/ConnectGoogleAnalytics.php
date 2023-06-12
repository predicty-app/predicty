<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class ConnectGoogleAnalytics
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[Assert\NotBlank(message: 'You must provide a refresh token')]
    public string $oauthRefreshToken;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    #[Assert\NotBlank(message: 'You must provide a GA4 ID')]
    public string $ga4Id;

    public function __construct(Ulid $userId, Ulid $accountId, string $oauthRefreshToken, string $ga4Id)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->oauthRefreshToken = $oauthRefreshToken;
        $this->ga4Id = $ga4Id;
    }
}
