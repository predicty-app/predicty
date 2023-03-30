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
use Brick\Money\Currency;

class FacebookCsvHandler extends AbstractFileImportHandler
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

    public function __construct(
        private CampaignFactory $campaignFactory,
        private AdSetFactory $adSetFactory,
        private AdFactory $adFactory,
        private AdStatsFactory $adStatsFactory,
    ) {
        parent::__construct(FileImportType::FACEBOOK_CSV);
    }

    public function processRecord(array $record, FileImportContext $context): void
    {
        $currency = Currency::of('PLN');

        $campaign = $this->campaignFactory->make(
            $context->getUserId(),
            $record[self::HEADER_CAMPAIGN_NAME],
            $record[self::HEADER_CAMPAIGN_ID],
        );

        $adSet = $this->adSetFactory->make(
            $campaign,
            '',
            $record[self::HEADER_AD_SET_ID]
        );

        $ad = $this->adFactory->make(
            $adSet,
            $record[self::HEADER_AD_NAME],
            $record[self::HEADER_AD_ID],
        );

        $this->adStatsFactory->make(
            ad: $ad,
            date: DateHelper::fromString($record[self::HEADER_DAY]),
            results: (int) $record[self::HEADER_RESULTS],
            costPerResult: MoneyHelper::amount((float) $record[self::HEADER_COST_PER_RESULT], $currency),
            amountSpent: MoneyHelper::amount((float) $record[self::HEADER_AMOUNT_SPENT_PLN], $currency)
        );
    }
}
