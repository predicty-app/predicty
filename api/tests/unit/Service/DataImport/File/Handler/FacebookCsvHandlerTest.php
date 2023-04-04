<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Entity\Campaign;
use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\Handler\FacebookCsvHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\DataImport\File\Handler\FacebookCsvHandler
 */
class FacebookCsvHandlerTest extends TestCase
{
    public function test_process_record_creates_campaign(): void
    {
        $record = [
            'Day' => '2021-01-01',
            'Ad name' => 'Test Ad',
            'Campaign name' => 'Test Campaign',
            'Results' => '100',
            'Cost per result' => '100',
            'Amount spent (PLN)' => '100',
            'Ad ID' => 'ad-id-100',
            'Ad set ID' => 'adset-id-100',
            'Campaign ID' => 'campaign-id-100',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);
        $campaignFactory->expects($this->once())->method('make')->with(
            $this->equalTo(123),
            $this->equalTo('Test Campaign'),
            $this->equalTo('campaign-id-100')
        );

        $handler = new FacebookCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad_set(): void
    {
        $record = [
            'Day' => '2021-01-01',
            'Ad name' => 'Test Ad',
            'Campaign name' => 'Test Campaign',
            'Results' => '100',
            'Cost per result' => '100',
            'Amount spent (PLN)' => '100',
            'Ad ID' => 'ad-id-100',
            'Ad set ID' => 'adset-id-100',
            'Campaign ID' => 'campaign-id-100',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adSetFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo(''),
            $this->equalTo('adset-id-100'),
        );
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new FacebookCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }

    public function test_process_record_creates_ad(): void
    {
        $record = [
            'Day' => '2021-01-01',
            'Ad name' => 'Test Ad',
            'Campaign name' => 'Test Campaign',
            'Results' => '100',
            'Cost per result' => '100',
            'Amount spent (PLN)' => '100',
            'Ad ID' => 'ad-id-100',
            'Ad set ID' => 'adset-id-100',
            'Campaign ID' => 'campaign-id-100',
        ];

        $context = new FileImportContext(123, new FileImportMetadata());

        $adSetFactory = $this->createMock(AdSetFactory::class);
        $adSetFactory->expects($this->once())->method('make')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo(''),
            $this->equalTo('adset-id-100'),
        );
        $adStatsFactory = $this->createMock(AdStatsFactory::class);
        $adFactory = $this->createMock(AdFactory::class);
        $campaignFactory = $this->createMock(CampaignFactory::class);

        $handler = new FacebookCsvHandler($campaignFactory, $adSetFactory, $adFactory, $adStatsFactory);
        $handler->processRecord($record, $context);
    }
}
