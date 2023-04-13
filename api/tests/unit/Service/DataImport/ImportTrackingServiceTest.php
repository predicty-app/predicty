<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class ImportTrackingServiceTest extends TestCase
{
    public function test_run(): void
    {
        $this->markTestSkipped('This test is not yet implemented.');
        //        $importRepository = $this->createMock(ImportRepository::class);
        //
        //        $dataImportApi = $this->createMock(TrackableDataImportApi::class);
        //        $eventBus = $this->createMock(MessageBusInterface::class);
        //        $logger = new NullLogger();
        //        $service = new ImportTrackingService($importRepository, $dataImportApi, $eventBus, $logger);
        //
        //        $service->run(1, function (){});
    }
}
