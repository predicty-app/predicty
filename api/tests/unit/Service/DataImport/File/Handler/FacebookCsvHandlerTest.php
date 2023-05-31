<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Service\DataImport\DataImportApi;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\Handler\FacebookCsvHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata());

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateCampaign')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo('Test Campaign'),
            $this->equalTo('campaign-id-100')
        );

        $handler = new FacebookCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata());
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateAdSet')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo(''),
            $this->equalTo('adset-id-100'),
        );

        $handler = new FacebookCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata());
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateAd')->with(
            $this->isInstanceOf(AdSet::class),
            $this->equalTo('Test Ad'),
            $this->equalTo('ad-id-100'),
        );

        $handler = new FacebookCsvHandler($dataImportApi);
        $handler->processRecord($record, $context);
    }
}
