<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\Handler\GoogleAdsCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\DataImport\File\Handler\GoogleAdsCsvHandler
 */
class GoogleAdsCsvHandlerTest extends TestCase
{
    public function test_process_record_creates_campaign(): void
    {
        $record = [
            'Campaign' => 'test',
            'Conversions' => '100',
            'Cost' => '100',
            'Ad ID' => '100',
            'Ad group' => 'test',
            'Ad group ID' => '100',
            'Campaign ID' => '098f6bcd4621d373cade4e832627b4f6',
            'Currency code' => 'PLN',
            'Day' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata(['campaignName' => 'test']));

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);
        $campaignFactory->expects($this->once())->method('make')->with(
            $this->equalTo(123),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );

        $handler = new GoogleAdsCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad(): void
    {
        $record = [
            'Campaign' => 'test',
            'Conversions' => '100',
            'Cost' => '100',
            'Ad ID' => 'ad-id-100',
            'Ad group' => 'test',
            'Ad group ID' => '100',
            'Campaign ID' => '098f6bcd4621d373cade4e832627b4f6',
            'Currency code' => 'PLN',
            'Day' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $adFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(AdSet::class),
            $this->equalTo('Ad no. ad-id-100'),
            $this->equalTo('ad-id-100')
        );
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new GoogleAdsCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad_stats(): void
    {
        $record = [
            'Campaign' => 'test',
            'Conversions' => '100',
            'Cost' => '100',
            'Ad ID' => '100',
            'Ad group' => 'test',
            'Ad group ID' => '100',
            'Campaign ID' => '098f6bcd4621d373cade4e832627b4f6',
            'Currency code' => 'PLN',
            'Day' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adStatsFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Ad::class),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(100),
            $this->equalTo(Money::of(1, 'PLN')),
            $this->equalTo(Money::of(100, 'PLN')),
        );
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new GoogleAdsCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad_set(): void
    {
        $record = [
            'Campaign' => 'test',
            'Conversions' => '100',
            'Cost' => '100',
            'Ad ID' => '100',
            'Ad group' => 'test ad group',
            'Ad group ID' => '100',
            'Campaign ID' => '098f6bcd4621d373cade4e832627b4f6',
            'Currency code' => 'PLN',
            'Day' => '2021-01-01',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adSetFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo('test ad group'),
            $this->equalTo('100')
        );
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new GoogleAdsCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }
}
