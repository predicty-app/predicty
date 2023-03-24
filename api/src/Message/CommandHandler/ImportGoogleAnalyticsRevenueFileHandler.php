<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportGoogleAnalyticsRevenueFile;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Google\GoogleAnalytics\GoogleAnalyticsRevenueCsvImporter;
use League\Flysystem\FilesystemReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class ImportGoogleAnalyticsRevenueFileHandler
{
    public function __construct(
        private ImportTrackingService $importTrackingService,
        private GoogleAnalyticsRevenueCsvImporter $googleAnalyticsRevenueCsvImporter,
        private FilesystemReader $filesystemReader
    ) {
    }

    public function __invoke(ImportGoogleAnalyticsRevenueFile $command): void
    {
        $this->importTrackingService->run($command->importId, function () use ($command): void {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->googleAnalyticsRevenueCsvImporter->import($command->userId, $stream);
        });
    }
}
