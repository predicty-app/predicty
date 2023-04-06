<?php

declare(strict_types=1);

namespace App\Service\DataImport\File\Handler;

use App\Entity\FileImportType;
use App\Service\Clock\Clock;
use App\Service\DataImport\File\AbstractCsvFileImportHandler;
use App\Service\DataImport\File\FileImportContext;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use Brick\Money\Money;

class SimplifiedCsvHandler extends AbstractCsvFileImportHandler
{
    private const HEADER_AD_NAME = 'Ad Name';
    private const HEADER_SPENT = 'Spent';
    private const HEADER_CURRENCY = 'Currency';
    private const HEADER_DATE = 'Date';

    public function processRecord(array $record, FileImportContext $context): void
    {
        $campaignName = $context->getMetadata()->get('campaignName') ?? $this->getRandomCampaignName();
        $campaignExternalId = md5($campaignName);
        $adSetExternalId = md5('adset'.$campaignName);

        $campaign = $this->dataImportApi->getOrCreateCampaign(
            userId: $context->getUserId(),
            name: $campaignName,
            externalId: $campaignExternalId
        );

        $adset = $this->dataImportApi->getOrCreateAdSet(
            campaign: $campaign,
            name: sprintf('%s ad set', $campaignName),
            externalId: $adSetExternalId,
        );

        $ad = $this->dataImportApi->getOrCreateAd(
            adSet: $adset,
            name: $record[self::HEADER_AD_NAME],
            externalId: md5($record[self::HEADER_AD_NAME]),
        );

        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $this->dataImportApi->getOrCreateAdStats(
            ad: $ad,
            date: DateHelper::fromString($record[self::HEADER_DATE]),
            results: 0,
            costPerResult: Money::zero($currency),
            amountSpent: MoneyHelper::amount((float) $record[self::HEADER_SPENT], $currency)
        );
    }

    protected function getFileImportType(): FileImportType
    {
        return FileImportType::OTHER_SIMPLIFIED_CSV;
    }

    private function getRandomCampaignName(): string
    {
        return sprintf('Campaign %s', Clock::now()->format('YmdHi'));
    }
}
