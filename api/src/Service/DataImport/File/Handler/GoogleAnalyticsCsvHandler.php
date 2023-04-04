<?php

declare(strict_types=1);

namespace App\Service\DataImport\File\Handler;

use App\Entity\FileImportType;
use App\Service\DataImport\File\AbstractCsvFileImportHandler;
use App\Service\DataImport\File\FileImportContext;
use App\Service\Util\DateHelper;
use Brick\Money\Currency;
use Brick\Money\Money;

class GoogleAnalyticsCsvHandler extends AbstractCsvFileImportHandler
{
    private const HEADER_DATE = 'Date';
    private const HEADER_REVENUE = 'Revenue';
    private const HEADER_AOV = 'AOV';
    private const HEADER_CURRENCY = 'Currency';

    public function processRecord(array $record, FileImportContext $context): void
    {
        $currency = Currency::of($record[self::HEADER_CURRENCY]);
        $this->dataImportApi->getOrCreateDailyRevenue(
            userId: $context->getUserId(),
            date: DateHelper::fromString($record[self::HEADER_DATE]),
            revenue: Money::of((float) $record[self::HEADER_REVENUE], $currency),
            averageOrderValue: Money::of((float) $record[self::HEADER_AOV], $currency)
        );
    }

    protected function getFileImportType(): FileImportType
    {
        return FileImportType::GOOGLE_ANALYTICS_REVENUE;
    }
}
