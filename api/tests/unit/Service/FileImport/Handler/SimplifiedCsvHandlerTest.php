<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\FileImport\Handler;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\FileImport\FileImportContext;
use App\Service\FileImport\FileImportMetadata;
use App\Service\FileImport\Handler\SimplifiedCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\FileImport\Handler\SimplifiedCsvHandler
 */
class SimplifiedCsvHandlerTest extends TestCase
{
    public function test_process_record_creates_campaign(): void
    {
        $record = [
            'Ad Name' => 'test',
            'Spent' => '100',
            'Currency' => 'PLN',
            'Date' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata(['campaignName' => 'test']));

        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $adSetFactory = $this->createMock(AdSetFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);
        $campaignFactory->expects($this->once())->method('make')->with(
            $this->equalTo(123),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );

        $handler = new SimplifiedCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad_set(): void
    {
        $record = [
            'Ad Name' => 'test',
            'Spent' => '100',
            'Currency' => 'PLN',
            'Date' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata(['campaignName' => 'test']));

        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adSetFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo('test ad set'),
            $this->equalTo('d821729c549c4bd281bf89d10a868061')
        );
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new SimplifiedCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad(): void
    {
        $record = [
            'Ad Name' => 'test',
            'Spent' => '100',
            'Currency' => 'PLN',
            'Date' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata(['campaignName' => 'test']));

        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $adFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(AdSet::class),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );
        $adSetFactory = $this->createMock(AdSetFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new SimplifiedCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad_stats(): void
    {
        $record = [
            'Ad Name' => 'test',
            'Spent' => '100',
            'Currency' => 'PLN',
            'Date' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata(['campaignName' => 'test']));

        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adStatsFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Ad::class),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(0),
            $this->equalTo(Money::zero('PLN')),
            $this->equalTo(Money::ofMinor(10000, 'PLN'))
        );
        $adFactory = $this->createMock(AdFactory::class);
        $adSetFactory = $this->createMock(AdSetFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new SimplifiedCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }
}
