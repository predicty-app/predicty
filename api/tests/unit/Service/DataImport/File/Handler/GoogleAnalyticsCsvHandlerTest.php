<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Service\DataImport\DataImportApi;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\Handler\GoogleAnalyticsCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\DataImport\File\Handler\GoogleAnalyticsCsvHandler
 */
class GoogleAnalyticsCsvHandlerTest extends TestCase
{
    public function test_process_record(): void
    {
        $record = [
            'Date' => '2021-01-01',
            'Revenue' => '100',
            'AOV' => '100',
            'Currency' => 'PLN',
        ];

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertDailyRevenue')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(Money::of(100, 'PLN')),
            $this->equalTo(Money::of(100, 'PLN'))
        );

        $handler = new GoogleAnalyticsCsvHandler($dataImportApi);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_with_different_currency(): void
    {
        $record = [
            'Date' => '2021-01-01',
            'Revenue' => '100',
            'AOV' => '100',
            'Currency' => 'USD',
        ];

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertDailyRevenue')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(Money::of(100, 'USD')),
            $this->equalTo(Money::of(100, 'USD'))
        );

        $handler = new GoogleAnalyticsCsvHandler($dataImportApi);
        $handler->processRecord($record, $context);
    }
}
