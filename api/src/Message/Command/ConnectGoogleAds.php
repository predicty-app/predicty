<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class ConnectGoogleAds
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    #[Assert\NotBlank(message: 'You must provide a refresh token')]
    public string $refreshToken;

    #[Assert\NotBlank(message: 'You must provide a customer id')]
    public string $customerId;

    public function __construct(Ulid $userId, Ulid $accountId, string $refreshToken, string $customerId)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->refreshToken = $refreshToken;
        $this->customerId = $customerId;
    }
}
