<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportSimplifiedCsvFile;
use App\Service\DataImport\File\SimplifiedCsvFileImporter;
use App\Service\DataImport\ImportTrackingService;
use League\Flysystem\FilesystemReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class ImportSimplifiedCsvFileHandler
{
    public function __construct(
        private ImportTrackingService $importTrackingService,
        private SimplifiedCsvFileImporter $simplifiedCsvFileImporter,
        private FilesystemReader $filesystemReader
    ) {
    }

    public function __invoke(ImportSimplifiedCsvFile $command): void
    {
        $this->importTrackingService->run($command->importId, function () use ($command): void {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->simplifiedCsvFileImporter->import($command->userId, $stream, $command->campaignName);
        });
    }
}
