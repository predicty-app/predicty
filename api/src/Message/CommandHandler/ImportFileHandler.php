<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportFile;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\FileImportService;
use App\Service\DataImport\ImportTrackingService;
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
            $this->fileImportService->import(
                userId: $command->userId,
                filename: $command->filename,
                fileImportType: $command->fileImportType,
                metadata: new FileImportMetadata($command->metadata)
            );
        });
    }
}
