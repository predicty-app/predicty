<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProvider;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterGoogleOAuthCredentials
{
    public int $userId;
    public DataProvider $dataProvider;

    #[Assert\NotBlank(message: 'You must provide a refresh token')]
    public string $oauthRefreshToken;

    public function __construct(int $userId, DataProvider $dataProvider, string $oauthRefreshToken)
    {
        $this->dataProvider = $dataProvider;
        $this->oauthRefreshToken = $oauthRefreshToken;
        $this->userId = $userId;
    }
}
