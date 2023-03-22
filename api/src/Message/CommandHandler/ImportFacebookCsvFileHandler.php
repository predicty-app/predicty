<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\Import;
use App\Message\Command\ImportFacebookCsvFile;
use App\Repository\ImportRepository;
use App\Service\Facebook\CsvImporter\FacebookCsvImporter;
use League\Flysystem\FilesystemReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;
use Webmozart\Assert\Assert;

#[AsMessageHandler(fromTransport: 'async')]
class ImportFacebookCsvFileHandler
{
    public function __construct(
        private FacebookCsvImporter $facebookCsvImporter,
        private ImportRepository $importRepository,
        private FilesystemReader $filesystemReader
    ) {
    }

    public function __invoke(ImportFacebookCsvFile $command): void
    {
        $import = $this->importRepository->findFileImportById($command->importId);
        Assert::isInstanceOf($import, Import::class);

        $import->start();

        try {
            $stream = $this->filesystemReader->readStream($command->filename);
            $this->facebookCsvImporter->import($import->getUserId(), $stream);
            $import->success();
            $this->importRepository->save($import);
        } catch (Throwable $exception) {
            $import->fail($exception->getMessage());
            $this->importRepository->save($import);

            throw $exception;
        }
    }
}
