<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProvider;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterGoogleOAuthCredentials
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    public DataProvider $dataProvider;

    #[Assert\NotBlank(message: 'You must provide a refresh token')]
    public string $oauthRefreshToken;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $userId, Ulid $accountId, DataProvider $dataProvider, string $oauthRefreshToken)
    {
        $this->dataProvider = $dataProvider;
        $this->oauthRefreshToken = $oauthRefreshToken;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
