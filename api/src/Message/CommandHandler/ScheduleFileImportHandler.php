<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportFile;
use App\Message\Command\ScheduleFileImport;
use App\Service\ImportTracking\ImportTrackingService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler]
class ScheduleFileImportHandler
{
    public function __construct(
        private ImportTrackingService $importTrackingService,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(ScheduleFileImport $command): void
    {
        $import = $this->importTrackingService->createNewImport(
            $command->userId,
            $command->filename,
            $command->fileImportType
        );

        $command = new ImportFile(
            importId: $import->getId(),
            userId: $command->userId,
            fileImportType: $command->fileImportType,
            filename: $command->filename,
            metadata: $command->metadata
        );

        $this->commandBus->dispatch($command, [new DispatchAfterCurrentBusStamp()]);
    }
}