<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Message\Command\ImportFile;
use App\Message\Command\ScheduleFileImport;
use App\Service\DataImport\ImportTrackingService;
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
        $import = $this->importTrackingService->createNewFileImport(
            $command->userId,
            $command->accountId,
            $command->filename,
            $command->fileImportType
        );

        $command = new ImportFile(
            importId: $import->getId(),
            userId: $command->userId,
            accountId: $command->accountId,
            fileImportType: $command->fileImportType,
            filename: $command->filename,
            metadata: $command->metadata
        );

        $this->commandBus->dispatch($command, [new DispatchAfterCurrentBusStamp()]);
    }
}
