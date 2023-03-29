<?php

declare(strict_types=1);

namespace App\Service\FileImport\Handler;

use App\Entity\FileImportType;
use App\Factory\AdFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\Clock\Clock;
use App\Service\FileImport\AbstractFileImportHandler;
use App\Service\FileImport\FileImportContext;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use Brick\Money\Money;

class SimplifiedCsvHandler extends AbstractFileImportHandler
{
    private const HEADER_AD_NAME = 'Ad Name';
    private const HEADER_SPENT = 'Spent';
    private const HEADER_CURRENCY = 'Currency';
    private const HEADER_DATE = 'Date';

    public function __construct(
        private CampaignFactory $campaignFactory,
        private AdFactory $adFactory,
        private AdStatsFactory $adStatsFactory
    ) {
        parent::__construct(FileImportType::OTHER_SIMPLIFIED_CSV);
    }

    public function processRecord(array $record, FileImportContext $context): void
    {
        $campaignName = $context->getMetadata()->get('campaignName') ?? $this->getRandomCampaignName();
        $externalId = md5($campaignName);

        $campaign = $this->campaignFactory->make(
            userId: $context->getUserId(),
            name: $campaignName,
            externalId: $externalId
        );

        $ad = $this->adFactory->makeForCampaign(
            campaign: $campaign,
            name: $record[self::HEADER_AD_NAME],
            externalId: md5($record[self::HEADER_AD_NAME]),
        );

        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $this->adStatsFactory->make(
            ad: $ad,
            date: DateHelper::fromString($record[self::HEADER_DATE]),
            results: 0,
            costPerResult: Money::zero($currency),
            amountSpent: MoneyHelper::amount((float) $record[self::HEADER_SPENT], $currency)
        );
    }

    private function getRandomCampaignName(): string
    {
        return sprintf('Campaign %s', Clock::now()->format('YmdHi'));
    }
}
