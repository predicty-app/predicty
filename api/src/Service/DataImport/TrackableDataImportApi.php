<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\ImportResult;

interface TrackableDataImportApi extends DataImportApi
{
    public function track(int $importId, ImportResult $importResult = null): void;

    public function getImportResult(): ImportResult;
}
