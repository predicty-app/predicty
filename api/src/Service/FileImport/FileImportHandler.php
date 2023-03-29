<?php

declare(strict_types=1);

namespace App\Service\FileImport;

use App\Entity\FileImportType;

interface FileImportHandler
{
    public function getOffset(): int;

    public function getHeaderOffset(): int;

    public function processRecord(array $record, FileImportContext $context): void;

    public function supports(FileImportType $fileImportType): bool;
}
