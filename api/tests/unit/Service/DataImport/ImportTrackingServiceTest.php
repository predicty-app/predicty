<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport;

use App\Entity\FileImportType;
use App\Entity\Import;
use App\Entity\ImportResult;
use App\Message\Event\ImportCompleted;
use App\Repository\ImportRepository;
use App\Service\DataImport\ImportTrackingService;
use App\Service\DataImport\TraceableDataImportApi;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use RuntimeException;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\DataImport\ImportTrackingService
 */
class ImportTrackingServiceTest extends TestCase
{
    public function test_create_new_file_import(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($this->createMock(Import::class));

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturnCallback(fn ($message, $stamps) => new Envelope(new stdClass()));

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $fileImport = $service->createNewFileImport($userId, $accountId, 'test.csv', FileImportType::FACEBOOK_CSV);

        $this->assertInstanceOf(Import::class, $fileImport);
        $this->assertSame($userId, $fileImport->getUserId());
        $this->assertSame($accountId, $fileImport->getAccountId());
        $this->assertSame('test.csv', $fileImport->getFilename());
        $this->assertSame(FileImportType::FACEBOOK_CSV, $fileImport->getFileImportType());
    }

    public function test_run(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($this->createMock(Import::class));

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturnCallback(fn ($message, $stamps) => new Envelope(new stdClass()));

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $importDone = false;
        $service->run($userId, function () use (&$importDone): void {
            $importDone = true;
        });

        $this->assertTrue($importDone);
    }

    public function test_run_marks_import_as_completed(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $import = $this->createMock(Import::class);
        $import->expects($this->once())->method('complete')->with($this->isInstanceOf(ImportResult::class));

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);
        $importRepository->method('save')->with($import);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturnCallback(fn ($message, $stamps) => new Envelope(new stdClass()));

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $service->run($userId, function (): void {});
    }

    public function test_successful_import_emits_event(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $import = $this->createMock(Import::class);

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ImportCompleted::class))->willReturnCallback(
            fn ($message, $stamps) => new Envelope($message)
        );

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $service->run($userId, function (): void {});
    }

    public function test_run_marks_import_as_started(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $import = $this->createMock(Import::class);
        $import->expects($this->once())->method('start')->with();

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);
        $importRepository->method('save')->with($import);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturnCallback(fn ($message, $stamps) => new Envelope(new stdClass()));

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $service->run($userId, function (): void {});
    }

    public function test_run_marks_import_as_failed_when_it_fails(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Something went wrong');

        $import = $this->createMock(Import::class);
        $import->expects($this->once())->method('fail')->with($this->isInstanceOf(ImportResult::class), 'Something went wrong');

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);
        $importRepository->method('save')->with($import);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturnCallback(fn ($message, $stamps) => new Envelope(new stdClass()));

        $dataImportApi = $this->createMock(TraceableDataImportApi::class);

        $logger = new NullLogger();
        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);

        $service->run($userId, function (): void {
            throw new RuntimeException('Something went wrong');
        });
    }
}
