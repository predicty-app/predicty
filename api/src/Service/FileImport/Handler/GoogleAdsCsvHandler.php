<?php

declare(strict_types=1);

namespace App\Service\FileImport\Handler;

use App\Entity\FileImportType;
use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\FileImport\AbstractFileImportHandler;
use App\Service\FileImport\FileImportContext;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;

class GoogleAdsCsvHandler extends AbstractFileImportHandler
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

    public function __construct(
        private CampaignFactory $campaignFactory,
        private AdSetFactory $adSetFactory,
        private AdFactory $adFactory,
        private AdStatsFactory $adStatsFactory,
    ) {
        parent::__construct(FileImportType::GOOGLE_ADS_CSV, 2, 2);
    }

    public function processRecord(array $record, FileImportContext $context): void
    {
        $campaign = $this->campaignFactory->make(
            $context->getUserId(),
            $record[self::HEADER_CAMPAIGN_NAME],
            $record[self::HEADER_CAMPAIGN_ID],
        );

        $adSet = $this->adSetFactory->make(
            $campaign,
            $record[self::HEADER_AD_SET_NAME],
            $record[self::HEADER_AD_SET_ID]
        );

        $ad = $this->adFactory->make(
            $adSet,
            sprintf('Ad no. %s', $record[self::HEADER_AD_ID]),
            $record[self::HEADER_AD_ID],
        );

        $currency = Currency::of($record[self::HEADER_CURRENCY]);

        $results = (int) $record[self::HEADER_RESULTS];
        $spent = MoneyHelper::amount((float) $record[self::HEADER_AMOUNT_SPENT], $currency);
        $cpr = $spent->isGreaterThan(0) ? $spent->dividedBy($results, RoundingMode::DOWN) : Money::zero($currency);

        $this->adStatsFactory->make(
            ad: $ad,
            date: DateHelper::fromString($record[self::HEADER_DAY]),
            results: $results,
            costPerResult: $cpr,
            amountSpent: $spent
        );
    }
}
