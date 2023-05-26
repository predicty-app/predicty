<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class ConnectFacebookAds
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    #[Assert\NotBlank(message: 'You must provide an access token')]
    public string $accessToken;

    #[Assert\NotBlank(message: 'You must provide an ad account id')]
    public string $adAccountId;

    public function __construct(Ulid $userId, Ulid $accountId, string $accessToken, string $adAccountId)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->accessToken = $accessToken;
        $this->adAccountId = $adAccountId;
    }
}
