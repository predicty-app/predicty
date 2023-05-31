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
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\MessageHandler\Command\SyncGoogleAnalyticsHandler
 */
class SyncGoogleAnalyticsHandlerTest extends TestCase
{
    public function test_sync(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $updater = $this->createMock(GoogleAnalyticsUpdater::class);
        $importTrackingService = $this->createMock(ImportTrackingService::class);
        $importTrackingService->expects($this->once())->method('createAndRunNewApiImport')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo(DataProvider::GOOGLE_ANALYTICS),
            $this->isInstanceOf(Closure::class)
        );

        $handler = new SyncGoogleAnalyticsHandler($updater, $importTrackingService);
        $handler->__invoke(new SyncGoogleAnalytics($userId, $accountId));
    }
}
