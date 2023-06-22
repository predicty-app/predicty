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

/**
 * CSV structure:
 * Ad Name,Spent,Currency,Date.
 */
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

        $campaign = $this->dataImportApi->upsertCampaign(
            userId: $context->getUserId(),
            accountId: $context->getAccountId(),
            name: $campaignName,
            externalId: $campaignExternalId
        );

        $adset = $this->dataImportApi->upsertAdSet(
            userId: $context->getUserId(),
            accountId: $context->getAccountId(),
            campaignId: $campaign->getId(),
            externalId: $adSetExternalId,
            name: sprintf('%s ad set', $campaignName),
        );

        $ad = $this->dataImportApi->upsertAd(
            userId: $context->getUserId(),
            accountId: $context->getAccountId(),
            campaignId: $campaign->getId(),
            adSetId: $adset->getId(),
            externalId: md5($record[self::HEADER_AD_NAME]),
            name: $record[self::HEADER_AD_NAME],
        );

        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $this->dataImportApi->upsertAdStats(
            userId: $context->getUserId(),
            accountId: $context->getAccountId(),
            adId: $ad->getId(),
            amountSpent: MoneyHelper::amount((float) $record[self::HEADER_SPENT], $currency),
            date: DateHelper::fromString($record[self::HEADER_DATE]),
            conversions: 0,
            clicks: 0,
            impressions: 0,
            leads: 0,
            costPerClick: Money::zero($currency),
            costPerResult: Money::zero($currency),
            costPerMil: Money::zero($currency)
        );

        $this->dataImportApi->flush();
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
