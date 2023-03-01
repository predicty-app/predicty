<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\DataProviderType;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterDataProvider
{
    public DataProviderType $type;

    #[Assert\NotBlank(message: 'You must provide a refresh token')]
    public string $oauthRefreshToken;

    public function __construct(DataProviderType $type, string $oauthRefreshToken)
    {
        $this->type = $type;
        $this->oauthRefreshToken = $oauthRefreshToken;
    }
}
