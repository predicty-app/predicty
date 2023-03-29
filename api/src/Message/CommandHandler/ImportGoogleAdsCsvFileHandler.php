<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportGoogleAdsCsvFile;
use App\Service\DataImport\File\GoogleAdsCsvImporter;
use App\Service\DataImport\ImportTrackingService;
use League\Flysystem\FilesystemReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class ImportGoogleAdsCsvFileHandler
{
    public function __construct(
        private GoogleAdsCsvImporter $googleAdsCsvImporter,
        private ImportTrackingService $importTrackingService,
        private FilesystemReader $filesystemReader
    ) {
    }

    public function __invoke(ImportGoogleAdsCsvFile $command): void
    {
        $this->importTrackingService->run($command->importId, function () use ($command): void {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->googleAdsCsvImporter->import($command->userId, $stream);
        });
    }
}
