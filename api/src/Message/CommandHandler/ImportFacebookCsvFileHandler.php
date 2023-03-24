<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportFacebookCsvFile;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Facebook\CsvImporter\FacebookCsvImporter;
use League\Flysystem\FilesystemReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class ImportFacebookCsvFileHandler
{
    public function __construct(
        private FacebookCsvImporter $facebookCsvImporter,
        private ImportTrackingService $importTrackingService,
        private FilesystemReader $filesystemReader
    ) {
    }

    public function __invoke(ImportFacebookCsvFile $command): void
    {
        $this->importTrackingService->run($command->importId, function () use ($command): void {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->facebookCsvImporter->import($command->userId, $stream);
        });
    }
}
