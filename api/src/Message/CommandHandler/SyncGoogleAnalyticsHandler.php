<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\DataProvider;
use App\Message\Command\SyncGoogleAnalytics;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Google\Analytics\GoogleAnalyticsUpdater;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SyncGoogleAnalyticsHandler
{
    public function __construct(
        private GoogleAnalyticsUpdater $googleAnalyticsUpdater,
        private ImportTrackingService $importTrackingService
    ) {
    }

    public function __invoke(SyncGoogleAnalytics $command): void
    {
        $this->importTrackingService->createAndRunNewApiImport(
            userId: $command->userId,
            dataProvider: DataProvider::GOOGLE_ANALYTICS,
            callback: fn () => $this->googleAnalyticsUpdater->update($command->userId)
        );
    }
}
