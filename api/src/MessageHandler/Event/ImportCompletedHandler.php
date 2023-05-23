<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\ImportCompleted;
use App\Repository\ImportRepository;
use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportCompletedHandler
{
    public function __construct(
        private ImportRepository $importRepository,
        private StartAndEndDateRecalculationService $startAndEndDateRecalculationService
    ) {
    }

    public function __invoke(ImportCompleted $event): void
    {
        $import = $this->importRepository->findById($event->importId);

        if ($import !== null) {
            $this->startAndEndDateRecalculationService->recalculate($import->getAccountId());
        }
    }
}
