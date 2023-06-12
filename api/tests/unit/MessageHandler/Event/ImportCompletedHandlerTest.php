<?php

declare(strict_types=1);

namespace App\Tests\Unit\MessageHandler\Event;

use App\Entity\Import;
use App\Message\Event\ImportCompleted;
use App\MessageHandler\Event\ImportCompletedHandler;
use App\Repository\ImportRepository;
use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\MessageHandler\Event\ImportCompletedHandler
 */
class ImportCompletedHandlerTest extends TestCase
{
    public function test_run_recalculation_on_completed_import(): void
    {
        $accountId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $importId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $import = $this->createMock(Import::class);
        $import->method('getAccountId')->willReturn($accountId);

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn($import);

        $startAndEndDateRecalculationService = $this->createMock(StartAndEndDateRecalculationService::class);
        $startAndEndDateRecalculationService->expects($this->once())->method('recalculate')->with($accountId);

        $handler = new ImportCompletedHandler($importRepository, $startAndEndDateRecalculationService);

        $handler->__invoke(new ImportCompleted($importId));
    }

    public function test_recalculation_is_skipped_if_import_does_not_exists(): void
    {
        $importId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $importRepository = $this->createMock(ImportRepository::class);
        $importRepository->method('findById')->willReturn(null);

        $startAndEndDateRecalculationService = $this->createMock(StartAndEndDateRecalculationService::class);
        $startAndEndDateRecalculationService->expects($this->never())->method('recalculate');

        $handler = new ImportCompletedHandler($importRepository, $startAndEndDateRecalculationService);

        $handler->__invoke(new ImportCompleted($importId));
    }
}
