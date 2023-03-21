<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Message\Command\ImportFacebookCsvFile;
use App\Message\Command\ImportFile;
use App\Repository\ImportRepository;
use Psr\Clock\ClockInterface;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler]
class ImportFileHandler
{
    public function __construct(
        private ImportRepository $importRepository,
        private MessageBusInterface $commandBus,
        private ClockInterface $clock
    ) {
    }

    public function __invoke(ImportFile $command): void
    {
        if ($command->fileImportType !== FileImportType::FACEBOOK_CSV) {
            throw new RuntimeException(sprintf('Cannot create import. DataProviderType is not supported: %s', $command->fileImportType->value));
        }

        $import = new FileImport(
            userId: $command->userId,
            filename: $command->filename,
            dataProviderId: $command->dataProviderId,
            fileImportType: $command->fileImportType,
            createdAt: $this->clock->now()
        );

        $this->importRepository->save($import);

        $this->commandBus->dispatch(
            new ImportFacebookCsvFile($import->getId(), $command->filename),
            [new DispatchAfterCurrentBusStamp()]
        );
    }
}
