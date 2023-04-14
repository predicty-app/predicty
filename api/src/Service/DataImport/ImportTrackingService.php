<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Entity\Import;
use App\Entity\ImportResult;
use App\Message\Event\ImportCompleted;
use App\Message\Event\ImportFailed;
use App\Repository\ImportRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Throwable;

/**
 * Track information about a data import from various sources.
 */
class ImportTrackingService
{
    public function __construct(
        private ImportRepository $importRepository,
        private TrackableDataImportApi $dataImportApi,
        private MessageBusInterface $eventBus,
        private LoggerInterface $importLogger
    ) {
    }

    public function createNewFileImport(int $userId, string $filename, FileImportType $fileImportType): FileImport
    {
        $import = new FileImport($userId, $filename, $fileImportType);
        $this->importRepository->save($import);

        return $import;
    }

    public function run(int $importId, callable $callback): void
    {
        $this->markImportAsStarted($importId);
        $importResult = ImportResult::empty();
        $this->dataImportApi->track($importId, $importResult);

        try {
            $callback();
            $this->markImportAsComplete($importId, $importResult);
        } catch (Throwable $e) {
            $this->importLogger->error(sprintf('Import failed: %s', $e->getMessage()), ['exception' => $e]);
            $this->markImportAsFailed($importId, $e->getMessage());

            throw $e;
        }
    }

    private function markImportAsStarted(int $id): void
    {
        $import = $this->getImport($id);
        $import->start();
        $this->importRepository->save($import);
    }

    private function markImportAsFailed(int $id, string $message = ''): void
    {
        $import = $this->getImport($id);
        $import->fail($message);
        $this->importRepository->save($import);
        $this->eventBus->dispatch(new ImportFailed($id), [new DispatchAfterCurrentBusStamp()]);
    }

    private function markImportAsComplete(int $id, ImportResult $importResult): void
    {
        $import = $this->getImport($id);
        $import->complete($importResult);
        $this->importRepository->save($import);
        $this->eventBus->dispatch(new ImportCompleted($id), [new DispatchAfterCurrentBusStamp()]);
    }

    private function getImport(int $id): Import
    {
        $import = $this->importRepository->findById($id);
        assert($import instanceof Import);

        return $import;
    }
}
