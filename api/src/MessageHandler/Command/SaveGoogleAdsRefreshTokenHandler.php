<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\GoogleAdsCredentials;
use App\Message\Command\SaveGoogleAdsRefreshToken;
use App\Repository\GoogleAdsCredentialsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SaveGoogleAdsRefreshTokenHandler
{
    public function __construct(private GoogleAdsCredentialsRepository $repository)
    {
    }

    public function __invoke(SaveGoogleAdsRefreshToken $command): void
    {
        $credentials = $this->repository->findOrCreate($command->userId);
        assert($credentials instanceof GoogleAdsCredentials);
        $credentials->setRefreshToken($command->refreshToken);
        $this->repository->save($credentials);
    }
}
