<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\DataProvider;
use App\Message\Command\SyncFacebookAds;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Facebook\FacebookAdsUpdater;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SyncFacebookAdsHandler
{
    public function __construct(
        private FacebookAdsUpdater $facebookAdsUpdater,
        private ImportTrackingService $importTrackingService
    ) {
    }

    public function __invoke(SyncFacebookAds $command): void
    {
        $this->importTrackingService->createAndRunNewApiImport(
            userId: $command->userId,
            accountId: $command->accountId,
            connectedAccountId: $command->connectedAccountId,
            dataProvider: DataProvider::FACEBOOK_ADS,
            callback: fn () => $this->facebookAdsUpdater->update($command->userId, $command->accountId, $command->connectedAccountId)
        );
    }
}
