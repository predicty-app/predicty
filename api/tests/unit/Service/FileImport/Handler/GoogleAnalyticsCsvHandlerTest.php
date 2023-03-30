<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\FileImport\Handler;

use App\Factory\DailyRevenueFactory;
use App\Service\FileImport\FileImportContext;
use App\Service\FileImport\FileImportMetadata;
use App\Service\FileImport\Handler\GoogleAnalyticsCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\FileImport\Handler\GoogleAnalyticsCsvHandler
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

        $context = new FileImportContext(123, new FileImportMetadata());
        $factory = $this->createMock(DailyRevenueFactory::class);
        $factory->expects($this->once())->method('make')->with(
            userId: 123,
            date: new DateTimeImmutable('2021-01-01'),
            revenue: Money::of(100, 'PLN'),
            averageOrderValue: Money::of(100, 'PLN')
        );

        $handler = new GoogleAnalyticsCsvHandler($factory);
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

        $context = new FileImportContext(123, new FileImportMetadata());
        $factory = $this->createMock(DailyRevenueFactory::class);
        $factory->expects($this->once())->method('make')->with(
            userId: 123,
            date: new DateTimeImmutable('2021-01-01'),
            revenue: Money::of(100, 'USD'),
            averageOrderValue: Money::of(100, 'USD')
        );

        $handler = new GoogleAnalyticsCsvHandler($factory);
        $handler->processRecord($record, $context);
    }
}
