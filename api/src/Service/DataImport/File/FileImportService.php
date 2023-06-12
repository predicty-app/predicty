<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

use App\Entity\FileImportType;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Flysystem\FilesystemReader;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class FileImportService
{
    private const MAX_BATCH_SIZE = 50;
    private array $batch = [];

    /**
     * @param array<FileImportHandler> $fileImportHandlers
     */
    public function __construct(
        private FilesystemReader $filesystemReader,
        private EntityManagerInterface $entityManager,
        private iterable $fileImportHandlers = []
    ) {
    }

    public function import(
        Ulid $userId,
        Ulid $accountId,
        string $filename,
        FileImportType $fileImportType,
        ?FileImportMetadata $metadata = null
    ): void {
        $fileImportHandler = $this->getFileImportHandler($fileImportType);

        $stream = $this->filesystemReader->readStream($filename);
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset($fileImportHandler->getHeaderOffset());

        $stmt = Statement::create(offset: $fileImportHandler->getOffset());
        $records = $stmt->process($csv, array_map('trim', $csv->getHeader()));

        $metadata ??= new FileImportMetadata();
        $context = new FileImportContext($userId, $accountId, $metadata, $records->getHeader());

        foreach ($records as $record) {
            $this->batch[] = function () use ($record, $fileImportHandler, $context): void {
                $fileImportHandler->processRecord($record, $context);
            };

            $this->flush();
        }

        $this->flush(true);
    }

    private function flush(bool $force = false): void
    {
        if (count($this->batch) >= self::MAX_BATCH_SIZE || $force === true) {
            $this->entityManager->transactional(function (): void {
                foreach ($this->batch as $record) {
                    $record();
                }

                $this->batch = [];
                $this->entityManager->clear();
            });
        }
    }

    private function getFileImportHandler(FileImportType $fileImportType): FileImportHandler
    {
        foreach ($this->fileImportHandlers as $fileImportHandler) {
            if ($fileImportHandler->supports($fileImportType)) {
                return $fileImportHandler;
            }
        }

        throw new RuntimeException(sprintf('No file import handler found for type "%s"', $fileImportType->name));
    }
}
