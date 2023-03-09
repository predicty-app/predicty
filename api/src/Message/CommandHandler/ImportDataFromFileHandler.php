<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Factory\ImportFactory;
use App\Message\Command\ImportDataFromFacebookCsvFile;
use App\Message\Command\ImportDataFromFile;
use App\Repository\ImportRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler]
class ImportDataFromFileHandler
{
    public function __construct(
        private ImportFactory $importFactory,
        private ImportRepository $importRepository,
        private MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(ImportDataFromFile $command): void
    {
        $import = $this->importFactory->createFacebookFileImport($command->userId, $command->filename);
        $this->importRepository->save($import);

        $this->commandBus->dispatch(
            new ImportDataFromFacebookCsvFile($import->getId(), $command->filename),
            [new DispatchAfterCurrentBusStamp()]
        );
    }
}
