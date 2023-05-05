<?php

declare(strict_types=1);

namespace App\Tests\Unit\MessageHandler\Event;

use App\Entity\Import;
use App\Message\Event\ImportCompleted;
use App\MessageHandler\Event\ImportCompletedHandler;
use App\Repository\ImportRepository;
use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\MessageHandler\Event\ImportCompletedHandler
 */
class ImportCompletedHandlerTest extends TestCase
{
    public function test_run_recalculation_login_on_completed_import(): void
    {
        $import = $this->createMock(Import::class);
        $import->method('getUserId')->willReturn(10);

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);

        $startAndEndDateRecalculationService = $this->createMock(StartAndEndDateRecalculationService::class);
        $startAndEndDateRecalculationService->expects($this->once())->method('recalculate')->with(10);

        $handler = new ImportCompletedHandler($importRepository, $startAndEndDateRecalculationService);

        $handler->__invoke(new ImportCompleted(1));
    }

    public function test_recalculation_is_skipped_if_import_does_not_exists(): void
    {
        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn(null);

        $startAndEndDateRecalculationService = $this->createMock(StartAndEndDateRecalculationService::class);
        $startAndEndDateRecalculationService->expects($this->never())->method('recalculate');

        $handler = new ImportCompletedHandler($importRepository, $startAndEndDateRecalculationService);

        $handler->__invoke(new ImportCompleted(1));
    }
}
