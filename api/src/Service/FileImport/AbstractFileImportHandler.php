<?php

declare(strict_types=1);

namespace App\Service\FileImport;

use App\Entity\FileImportType;

abstract class AbstractFileImportHandler implements FileImportHandler
{
    public function __construct(
        protected FileImportType $fileImportType,
        protected int $offset = 0,
        protected int $headerOffset = 0
    ) {
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
        return $this->fileImportType === $fileImportType;
    }
}
