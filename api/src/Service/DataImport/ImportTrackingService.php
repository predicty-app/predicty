<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\ApiImport;
use App\Entity\DataProvider;
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
use Symfony\Component\Uid\Ulid;
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

    public function createNewFileImport(Ulid $userId, Ulid $accountId, string $filename, FileImportType $fileImportType): FileImport
    {
        $import = new FileImport(new Ulid(), $userId, $accountId, $filename, $fileImportType);
        $this->importRepository->save($import);

        return $import;
    }

    public function createNewApiImport(Ulid $userId, Ulid $accountId, DataProvider $dataProvider): Import
    {
        $import = new ApiImport(new Ulid(), $userId, $accountId, $dataProvider);
        $this->importRepository->save($import);

        return $import;
    }

    public function createAndRunNewApiImport(Ulid $userId, Ulid $accountId, DataProvider $dataProvider, callable $callback): void
    {
        $import = $this->createNewApiImport($userId, $accountId, $dataProvider);
        $this->run($import->getId(), $callback);
    }

    public function run(Ulid $importId, callable $callback): void
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

    private function markImportAsStarted(Ulid $id): void
    {
        $import = $this->getImport($id);
        $import->start();
        $this->importRepository->save($import);
    }

    private function markImportAsFailed(Ulid $id, string $message = ''): void
    {
        $import = $this->getImport($id);
        $import->fail($message);
        $this->importRepository->save($import);
        $this->eventBus->dispatch(new ImportFailed($id), [new DispatchAfterCurrentBusStamp()]);
    }

    private function markImportAsComplete(Ulid $id, ImportResult $importResult): void
    {
        $import = $this->getImport($id);
        $import->complete($importResult);
        $this->importRepository->save($import);
        $this->eventBus->dispatch(new ImportCompleted($id), [new DispatchAfterCurrentBusStamp()]);
    }

    private function getImport(Ulid $id): Import
    {
        $import = $this->importRepository->findById($id);
        assert($import instanceof Import);

        return $import;
    }
}
