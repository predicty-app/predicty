<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\FileImportType;
use App\Message\Command\ImportFacebookCsvFile;
use App\Message\Command\ImportFile;
use App\Message\Command\ImportGoogleAnalyticsRevenueFile;
use App\Service\DataImport\ImportTrackingService;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler]
class ImportFileHandler
{
    public function __construct(
        private ImportTrackingService $importTrackingService,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(ImportFile $command): void
    {
        $import = $this->importTrackingService->createNewImport(
            $command->userId,
            $command->filename,
            $command->fileImportType
        );

        $importCommand = match ($command->fileImportType) {
            FileImportType::FACEBOOK_CSV => new ImportFacebookCsvFile($import->getId(), $command->userId, $command->filename),
            FileImportType::GOOGLE_ANALYTICS_REVENUE => new ImportGoogleAnalyticsRevenueFile($import->getId(), $command->userId, $command->filename),
            default => throw new RuntimeException(sprintf('Cannot create import - file type is not supported: %s', $command->fileImportType->value)),
        };

        $this->commandBus->dispatch($importCommand, [new DispatchAfterCurrentBusStamp()]);
    }
}
