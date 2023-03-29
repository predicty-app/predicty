<?php

declare(strict_types=1);

namespace App\Service\FileImport\Handler;

use App\Entity\FileImportType;
use App\Factory\DailyRevenueFactory;
use App\Service\FileImport\AbstractFileImportHandler;
use App\Service\FileImport\FileImportContext;
use App\Service\Util\DateHelper;
use Brick\Money\Currency;
use Brick\Money\Money;

class GoogleAnalyticsCsvHandler extends AbstractFileImportHandler
{
    private const HEADER_DATE = 'Date';
    private const HEADER_REVENUE = 'Revenue';
    private const HEADER_AOV = 'AOV';
    private const HEADER_CURRENCY = 'Currency';

    public function __construct(private DailyRevenueFactory $dailyRevenueFactory)
    {
        parent::__construct(FileImportType::GOOGLE_ANALYTICS_REVENUE);
    }

    public function processRecord(array $record, FileImportContext $context): void
    {
        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $this->dailyRevenueFactory->make(
            userId: $context->getUserId(),
            date: DateHelper::fromString($record[self::HEADER_DATE]),
            revenue: Money::of((float) $record[self::HEADER_REVENUE], $currency),
            averageOrderValue: Money::of((float) $record[self::HEADER_AOV], $currency)
        );
    }
}
