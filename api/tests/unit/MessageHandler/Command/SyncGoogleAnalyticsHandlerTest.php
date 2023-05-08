<?php

declare(strict_types=1);

namespace App\Tests\Unit\MessageHandler\Command;

use App\Entity\DataProvider;
use App\Message\Command\SyncGoogleAnalytics;
use App\MessageHandler\Command\SyncGoogleAnalyticsHandler;
use App\Service\DataImport\ImportTrackingService;
use App\Service\Google\Analytics\GoogleAnalyticsUpdater;
use Closure;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\MessageHandler\Command\SyncGoogleAnalyticsHandler
 */
class SyncGoogleAnalyticsHandlerTest extends TestCase
{
    public function test_sync(): void
    {
        $updater = $this->createMock(GoogleAnalyticsUpdater::class);
        $importTrackingService = $this->createMock(ImportTrackingService::class);
        $importTrackingService->expects($this->once())->method('createAndRunNewApiImport')->with(
            $this->equalTo(3),
            $this->equalTo(DataProvider::GOOGLE_ANALYTICS),
            $this->isInstanceOf(Closure::class)
        );

        $handler = new SyncGoogleAnalyticsHandler($updater, $importTrackingService);
        $handler->__invoke(new SyncGoogleAnalytics(3));
    }
}
