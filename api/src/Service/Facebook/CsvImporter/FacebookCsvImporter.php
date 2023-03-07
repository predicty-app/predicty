<?php

declare(strict_types=1);

namespace App\Service\Facebook\CsvImporter;

use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use Brick\Money\Currency;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class FacebookCsvImporter
{
    private const HEADER_DAY = 'Day';
    private const HEADER_AD_NAME = 'Ad name';
    private const HEADER_CAMPAIGN_NAME = 'Campaign name';
//    private const HEADER_RESULT_TYPE = 'Result type';
    private const HEADER_RESULTS = 'Results';
    private const HEADER_COST_PER_RESULT = 'Cost per result';
    private const HEADER_AMOUNT_SPENT_PLN = 'Amount spent (PLN)';
    private const HEADER_AD_ID = 'Ad ID';
    private const HEADER_AD_SET_ID = 'Ad set ID';
    private const HEADER_CAMPAIGN_ID = 'Campaign ID';
//    private const HEADER_REPORTING_STARTS = 'Reporting starts';
//    private const HEADER_REPORTING_ENDS = 'Reporting ends';

    private const MAX_BATCH_SIZE = 100;

    /**
     * @var array<callable>
     */
    private array $batch = [];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CampaignFactory $campaignFactory,
        private AdSetFactory $adSetFactory,
        private AdFactory $adFactory,
        private AdStatsFactory $adStatsFactory,
    ) {
    }

    public function import(int $userId, string $filename, ?\Closure $callback = null): void
    {
        $csv = Reader::createFromPath($filename);
        $csv->setHeaderOffset(0);

        $stmt = Statement::create();
        $records = $stmt->process($csv);

        CsvHeadersValidator::validate($records->getHeader());

        foreach ($records as $record) {
            $this->batch[] = function () use ($userId, $record): void {
                $campaign = $this->campaignFactory->make(
                    $userId,
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

                // todo extract currency
                $currency = Currency::of('PLN');
                $this->adStatsFactory->make(
                    ad: $ad,
                    date: DateHelper::fromString($record[self::HEADER_DAY]),
                    results: (int) $record[self::HEADER_RESULTS],
                    costPerResult: MoneyHelper::amount((float) $record[self::HEADER_COST_PER_RESULT], $currency),
                    amountSpent: MoneyHelper::amount((float) $record[self::HEADER_AMOUNT_SPENT_PLN], $currency)
                );
            };

            if ($callback !== null) {
                $callback($record);
            }

            $this->flush();
        }

        $this->flush(true);
    }

    private function flush(bool $force = false): void
    {
        if (count($this->batch) >= self::MAX_BATCH_SIZE || $force === true) {
            $this->entityManager->transactional(function (): void {
                foreach ($this->batch as $record) {
                    $record();
                }

                $this->batch = [];
                $this->entityManager->clear();
            });
        }
    }
}
