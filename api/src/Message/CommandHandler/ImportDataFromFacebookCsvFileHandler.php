<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\ImportDataFromFacebookCsvFile;
use App\Repository\ImportRepository;
use App\Service\Facebook\CsvImporter\FacebookCsvImporter;
use League\Flysystem\FilesystemReader;
use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler(fromTransport: 'async')]
class ImportDataFromFacebookCsvFileHandler
{
    public function __construct(
        private FacebookCsvImporter $facebookCsvImporter,
        private ImportRepository $importRepository,
        private FilesystemReader $filesystemReader,
        private ClockInterface $clock
    ) {
    }

    public function __invoke(ImportDataFromFacebookCsvFile $command): void
    {
        $import = $this->importRepository->findFileImportById($command->importId);
        $import->start($this->clock->now());

        try {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->facebookCsvImporter->import($import->getUserId(), $stream);
            $import->success($this->clock->now());
            $this->importRepository->save($import);
        } catch (Throwable $exception) {
            $import->fail($exception->getMessage(), $this->clock->now());
            $this->importRepository->save($import);

            throw $exception;
        }
    }
}
