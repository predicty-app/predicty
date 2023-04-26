<?php

declare(strict_types=1);

namespace App\Service\DataImport\File\Handler;

use App\Entity\FileImportType;
use App\Service\DataImport\File\AbstractCsvFileImportHandler;
use App\Service\DataImport\File\FileImportContext;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;

/**
 * CSV structure:
 * Day,Campaign,Campaign ID,Ad group,Ad group ID,Ad ID,Conversions,Cost,Currency code.
 */
class GoogleAdsCsvHandler extends AbstractCsvFileImportHandler
{
    private const HEADER_CAMPAIGN_NAME = 'Campaign';
    private const HEADER_RESULTS = 'Conversions';
    private const HEADER_AMOUNT_SPENT = 'Cost';
    private const HEADER_AD_ID = 'Ad ID';
    private const HEADER_AD_SET_NAME = 'Ad group';
    private const HEADER_AD_SET_ID = 'Ad group ID';
    private const HEADER_CAMPAIGN_ID = 'Campaign ID';
    private const HEADER_CURRENCY = 'Currency code';
    private const HEADER_DAY = 'Day';

    public function processRecord(array $record, FileImportContext $context): void
    {
        $campaign = $this->dataImportApi->getOrCreateCampaign(
            $context->getUserId(),
            $record[self::HEADER_CAMPAIGN_NAME],
            $record[self::HEADER_CAMPAIGN_ID],
        );

        $adSet = $this->dataImportApi->getOrCreateAdSet(
            $campaign,
            $record[self::HEADER_AD_SET_NAME],
            $record[self::HEADER_AD_SET_ID]
        );

        $ad = $this->dataImportApi->getOrCreateAd(
            $adSet,
            sprintf('Ad no. %s', $record[self::HEADER_AD_ID]),
            $record[self::HEADER_AD_ID],
        );

        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $results = (int) $record[self::HEADER_RESULTS];
        $spent = MoneyHelper::amount((float) $record[self::HEADER_AMOUNT_SPENT], $currency);
        $cpr = $spent->isGreaterThan(0) ? $spent->dividedBy($results, RoundingMode::DOWN) : Money::zero($currency);

        $this->dataImportApi->getOrCreateAdStats(
            ad: $ad,
            date: DateHelper::fromString($record[self::HEADER_DAY]),
            results: $results,
            costPerResult: $cpr,
            amountSpent: $spent
        );
    }

    public function getOffset(): int
    {
        return 2;
    }

    public function getHeaderOffset(): int
    {
        return 2;
    }

    protected function getFileImportType(): FileImportType
    {
        return FileImportType::GOOGLE_ADS_CSV;
    }
}
