<?php

declare(strict_types=1);

namespace App\Service\Google\GoogleAnalytics;

use App\Factory\DailyRevenueFactory;
use App\Service\Util\DateHelper;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class GoogleAnalyticsRevenueCsvImporter
{
    private const HEADER_DATE = 'Date';
    private const HEADER_REVENUE = 'Revenue';
    private const HEADER_AOV = 'AOV';
    private const HEADER_CURRENCY = 'Currency';
    private const MAX_BATCH_SIZE = 100;

    /**
     * @var array<callable>
     */
    private array $batch = [];

    public function __construct(private EntityManagerInterface $entityManager, private DailyRevenueFactory $dailyRevenueFactory)
    {
    }

    public function import(int $userId, mixed $stream): void
    {
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset(0);

        $stmt = Statement::create();
        $records = $stmt->process($csv, array_map('trim', $csv->getHeader()));

        foreach ($records as $record) {
            $this->batch[] = function () use ($userId, $record): void {
                $currency = Currency::of($record[self::HEADER_CURRENCY]);
                $this->dailyRevenueFactory->make(
                    userId: $userId,
                    date: DateHelper::fromString($record[self::HEADER_DATE]),
                    revenue: Money::of((float) $record[self::HEADER_REVENUE], $currency),
                    averageOrderValue: Money::of((float) $record[self::HEADER_AOV], $currency)
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
