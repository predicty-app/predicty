<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class GoogleAdsCsvImporter
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

    public function import(int $userId, mixed $stream, ?string $campaignName = null): void
    {
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset(2);

        $stmt = Statement::create(offset: 2);
        $records = $stmt->process($csv);

        foreach ($records as $record) {
            $this->batch[] = function () use ($userId, $record): void {
                $campaign = $this->campaignFactory->make(
                    $userId,
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
            };

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
