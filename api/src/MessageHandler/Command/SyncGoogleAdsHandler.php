<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\DataProvider;
use App\Message\Command\SyncGoogleAds;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Google\Ads\GoogleAdsUpdater;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SyncGoogleAdsHandler
{
    public function __construct(
        private GoogleAdsUpdater $googleAdsUpdater,
        private ImportTrackingService $importTrackingService
    ) {
    }

    public function __invoke(SyncGoogleAds $command): void
    {
        $this->importTrackingService->createAndRunNewApiImport(
            userId: $command->userId,
            accountId: $command->accountId,
            connectedAccountId: $command->connectedAccountId,
            dataProvider: DataProvider::GOOGLE_ADS,
            callback: fn () => $this->googleAdsUpdater->update($command->userId, $command->accountId, $command->connectedAccountId)
        );
    }
}
