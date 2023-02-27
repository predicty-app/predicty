<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GoogleAdsCredentials extends DataProviderCredentials
{
    public function __construct(int $userId, string $refreshToken = '')
    {
        parent::__construct($userId, DataProviderType::GOOGLE_ADS, ['refreshToken' => $refreshToken]);
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->setCredentials(['refreshToken' => $refreshToken]);
    }

    public function getRefreshToken(): string
    {
        return $this->getCredentials()['refreshToken'];
    }
}
