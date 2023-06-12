<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataImport\File\Handler;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Service\DataImport\DataImportApi;
use App\Service\DataImport\File\FileImportContext;
use App\Service\DataImport\File\FileImportMetadata;
use App\Service\DataImport\File\Handler\SimplifiedCsvHandler;
use Brick\Money\Money;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\DataImport\File\Handler\SimplifiedCsvHandler
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata(['campaignName' => 'test']));
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateCampaign')->with(
            $this->equalTo($userId),
            $this->equalTo($accountId),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );

        $handler = new SimplifiedCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata(['campaignName' => 'test']));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateAdSet')->with(
            $this->isInstanceOf(Campaign::class),
            $this->equalTo('test ad set'),
            $this->equalTo('d821729c549c4bd281bf89d10a868061')
        );
        $handler = new SimplifiedCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata(['campaignName' => 'test']));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateAd')->with(
            $this->isInstanceOf(AdSet::class),
            $this->equalTo('test'),
            $this->equalTo('098f6bcd4621d373cade4e832627b4f6')
        );

        $handler = new SimplifiedCsvHandler($dataImportApi);
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

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $context = new FileImportContext($userId, $accountId, new FileImportMetadata(['campaignName' => 'test']));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getOrCreateAdStats')->with(
            $this->isInstanceOf(Ad::class),
            $this->equalTo(new DateTimeImmutable('2021-01-01')),
            $this->equalTo(0),
            $this->equalTo(Money::zero('PLN')),
            $this->equalTo(Money::ofMinor(10000, 'PLN'))
        );

        $handler = new SimplifiedCsvHandler($dataImportApi);
        $handler->processRecord($record, $context);
    }
}
