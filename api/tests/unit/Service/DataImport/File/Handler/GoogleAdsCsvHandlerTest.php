<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Service\DataImport\DataImportApi;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\Handler\GoogleAdsCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata(['campaignName' => 'test']));
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertCampaign')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );

        $handler = new GoogleAdsCsvHandler($dataImportApi);
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
            'Ad group ID' => 'adg100',
            'Campaign ID' => '098f6bcd4621d373cade4e832627b4f6',
            'Currency code' => 'PLN',
            'Day' => '2021-01-01',
        ];

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertAdSet')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->isInstanceOf(Ulid::class),
            $this->equalTo('adg100'),
            $this->equalTo('test ad group'),
        );

        $handler = new GoogleAdsCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertAd')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->isInstanceOf(Ulid::class),
            $this->isInstanceOf(Ulid::class),
            $this->equalTo('ad-id-100'),
            $this->equalTo('Ad no. ad-id-100'),
        );

        $handler = new GoogleAdsCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertAdStats')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->isInstanceOf(Ulid::class),
            $this->equalTo(Money::of(100, 'PLN')),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(100),
            $this->equalTo(0),
            $this->equalTo(0),
            $this->equalTo(0),
            $this->equalTo(Money::zero('PLN')),
            $this->equalTo(Money::of(1, 'PLN')),
            $this->equalTo(Money::zero('PLN')),
        );

        $handler = new GoogleAdsCsvHandler($dataImportApi);
        $handler->processRecord($record, $context);
    }
}
