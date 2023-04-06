<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

use App\Entity\FileImportType;
use App\Service\DataImport\DataImportApi;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCsvFileImportHandler implements FileImportHandler, LoggerAwareInterface
{
    protected LoggerInterface $logger;

    final public function __construct(
        protected DataImportApi $dataImportApi,
        protected int $offset = 0,
        protected int $headerOffset = 0
    ) {
        $this->logger = new NullLogger();
    }

    #[Required]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getHeaderOffset(): int
    {
        return $this->headerOffset;
    }

    abstract public function processRecord(array $record, FileImportContext $context): void;

    public function supports(FileImportType $fileImportType): bool
    {
        return $this->getFileImportType() === $fileImportType;
    }

    abstract protected function getFileImportType(): FileImportType;
}
