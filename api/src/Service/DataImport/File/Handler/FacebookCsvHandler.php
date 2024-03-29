<?php

declare(strict_types=1);

namespace App\Service\DataImport\File\Handler;

use App\Entity\FileImportType;
use App\Service\DataImport\File\AbstractCsvFileImportHandler;
use App\Service\DataImport\File\FileImportContext;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use Brick\Money\Money;

/**
 * CSV structure:
 * Day,Ad name,Campaign name,Results,Cost per result,Amount spent (PLN),Ad ID,Ad set ID,Campaign ID.
 *
 * @todo extract currency
 */
class FacebookCsvHandler extends AbstractCsvFileImportHandler
{
    private const HEADER_DAY = 'Day';
    private const HEADER_AD_NAME = 'Ad name';
    private const HEADER_CAMPAIGN_NAME = 'Campaign name';
    private const HEADER_RESULTS = 'Results';
    private const HEADER_COST_PER_RESULT = 'Cost per result';
    private const HEADER_AMOUNT_SPENT_PLN = 'Amount spent (PLN)';
    private const HEADER_AD_ID = 'Ad ID';
    private const HEADER_AD_SET_ID = 'Ad set ID';
    private const HEADER_CAMPAIGN_ID = 'Campaign ID';

    public function processRecord(array $record, FileImportContext $context): void
    {
        $currency = Currency::of('PLN');

        $campaign = $this->dataImportApi->upsertCampaign(
            $context->getUserId(),
            $context->getAccountId(),
            $record[self::HEADER_CAMPAIGN_NAME],
            $record[self::HEADER_CAMPAIGN_ID],
        );

        $adSet = $this->dataImportApi->upsertAdSet(
            $context->getUserId(),
            $context->getAccountId(),
            $campaign->getId(),
            $record[self::HEADER_AD_SET_ID],
            ''
        );

        $ad = $this->dataImportApi->upsertAd(
            $context->getUserId(),
            $context->getAccountId(),
            $campaign->getId(),
            $adSet->getId(),
            $record[self::HEADER_AD_ID],
            $record[self::HEADER_AD_NAME],
        );

        $this->dataImportApi->upsertAdInsights(
            userId: $context->getUserId(),
            accountId: $context->getAccountId(),
            adId: $ad->getId(),
            amountSpent: MoneyHelper::amount((float) $record[self::HEADER_AMOUNT_SPENT_PLN], $currency),
            date: DateHelper::fromString($record[self::HEADER_DAY]),
            conversions: (int) $record[self::HEADER_RESULTS],
            clicks: 0,
            impressions: 0,
            leads: 0,
            costPerClick: Money::zero($currency),
            costPerResult: MoneyHelper::amount((float) $record[self::HEADER_COST_PER_RESULT], $currency),
            costPerMil: Money::zero($currency)
        );

        $this->dataImportApi->flush();
    }

    protected function getFileImportType(): FileImportType
    {
        return FileImportType::FACEBOOK_CSV;
    }
}
