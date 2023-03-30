<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportFile;
use App\Service\FileImport\FileImportMetadata;
use App\Service\FileImport\FileImportService;
use App\Service\ImportTracking\ImportTrackingService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class ImportFileHandler
{
    public function __construct(
        private FileImportService $fileImportService,
        private ImportTrackingService $importTrackingService
    ) {
    }

    public function __invoke(ImportFile $command): void
    {
        $this->importTrackingService->run($command->importId, function () use ($command): void {
            $metadata = new FileImportMetadata($command->metadata);
            $this->fileImportService->import($command->userId, $command->filename, $command->fileImportType, $metadata);
        });
    }
}
