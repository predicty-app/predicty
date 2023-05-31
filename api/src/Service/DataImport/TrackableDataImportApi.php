<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\ImportResult;
use Symfony\Component\Uid\Ulid;

interface TrackableDataImportApi extends DataImportApi
{
    public function track(Ulid $importId, ImportResult $importResult = null): void;

    public function getImportResult(): ImportResult;
}
