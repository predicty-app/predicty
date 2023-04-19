<?php

declare(strict_types=1);

namespace App\Service\Google\Analytics;

use App\Service\DataImport\DataImportApi;
use App\Service\Util\DateHelper;
use App\Service\Util\MoneyHelper;
use Brick\Money\Currency;

class GoogleAnalyticsUpdater
{
    private const GA_DATE_FORMAT = 'Ymd';

    public function __construct(
        private GoogleAnalyticsGA4Api $googleAnalyticsApi,
        private DataImportApi $dataImportApi
    ) {
    }

    public function update(int $userId): void
    {
        foreach ($this->googleAnalyticsApi->getDailyRevenue($userId) as $revenue) {
            $this->dataImportApi->createDailyRevenueIfNotExists(
                userId: $userId,
                date: DateHelper::fromString($revenue['date'], self::GA_DATE_FORMAT),
                revenue: MoneyHelper::amount($revenue['revenue'], Currency::of('USD')),
                averageOrderValue: MoneyHelper::amount($revenue['averageOrderValue'], Currency::of('USD')),
            );
        }
    }
}
