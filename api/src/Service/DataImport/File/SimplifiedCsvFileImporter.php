<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

use App\Factory\AdFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class SimplifiedCsvFileImporter
{
    private const HEADER_AD_NAME = 'Ad Name';
    private const HEADER_SPENT = 'Spent';
    private const HEADER_CURRENCY = 'Currency';
    private const HEADER_DATE = 'Date';

    private const MAX_BATCH_SIZE = 100;

    /**
     * @var array<callable>
     */
    private array $batch = [];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CampaignFactory $campaignFactory,
        private AdFactory $adFactory,
        private AdStatsFactory $adStatsFactory,
    ) {
    }

    public function import(int $userId, mixed $stream, ?string $campaignName = null): void
    {
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset(0);

        $stmt = Statement::create();
        $records = $stmt->process($csv);

        $campaignName ??= sprintf('Campaign %s', date('YmdHi'));
        $externalId = md5($campaignName);

        $campaign = $this->campaignFactory->make(
            userId: $userId,
            name: $campaignName,
            externalId: $externalId
        );

        foreach ($records as $record) {
            $this->batch[] = function () use ($record, $campaign): void {
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
