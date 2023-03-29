<?php

declare(strict_types=1);

namespace App\Service\ImportTracking;

use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Entity\Import;
use App\Repository\ImportRepository;
use Closure;
use Psr\Log\LoggerInterface;
use Throwable;

class ImportTrackingService
{
    public function __construct(private ImportRepository $importRepository, private LoggerInterface $importLogger)
    {
    }

    public function createNewImport(int $userId, string $filename, FileImportType $fileImportType): Import
    {
        $import = new FileImport(
            userId: $userId,
            filename: $filename,
            dataProvider: $fileImportType->getDataProvider(),
            fileImportType: $fileImportType,
        );

        $this->importRepository->save($import);

        return $import;
    }

    public function run(int $importId, Closure $closure): void
    {
        $this->markImportAsStarted($importId);

        try {
            $closure();
            $this->markImportAsComplete($importId);
        } catch (Throwable $e) {
            $this->importLogger->error(sprintf('Import failed: %s', $e->getMessage()), ['exception' => $e]);
            $this->markImportAsFailed($importId, $e->getMessage());

            throw $e;
        }
    }

    private function markImportAsStarted(int $id): void
    {
        $import = $this->importRepository->findById($id);
        assert($import instanceof Import);

        $import->start();
        $this->importRepository->save($import);
    }

    private function markImportAsFailed(int $id, string $message = ''): void
    {
        $import = $this->importRepository->findById($id);
        assert($import instanceof Import);

        $import->fail($message);
        $this->importRepository->save($import);
    }

    private function markImportAsComplete(int $id): void
    {
        $import = $this->importRepository->findById($id);
        assert($import instanceof Import);

        $import->complete();
        $this->importRepository->save($import);
    }
}
