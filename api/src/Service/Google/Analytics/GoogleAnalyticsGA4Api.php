<?php

declare(strict_types=1);

namespace App\Service\Google\Analytics;

use App\Service\Clock\Clock;
use App\Service\Google\GoogleOAuth;
use DateInterval;
use DatePeriod;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Row;
use Symfony\Component\Uid\Ulid;

class GoogleAnalyticsGA4Api
{
    public function __construct(
        private GoogleAnalyticsCredentialsProvider $credentialsProvider,
        private GoogleOAuth $googleOAuth,
    ) {
    }

    /**
     * @return iterable<array{date: string, revenue: string, averageOrderValue: string}>
     */
    public function getDailyRevenue(Ulid $connectedAccountId): iterable
    {
        $credentials = $this->credentialsProvider->getCredentials($connectedAccountId);
        $this->googleOAuth->fetchAccessToken($credentials->getRefreshToken());

        $data = $this->getDefaults();
        $client = new BetaAnalyticsDataClient(['credentials' => $this->googleOAuth->getOAuth()]);
        $response = $client->runReport([
            'property' => 'properties/'.$credentials->getGA4Id(),
            'dateRanges' => [
                new DateRange(['start_date' => '7daysAgo', 'end_date' => 'yesterday']),
            ],
            'metrics' => [
                new Metric(['name' => 'purchaseRevenue']),
                new Metric(['name' => 'averagePurchaseRevenue']),
            ],
            'dimensions' => [
                new Dimension(['name' => 'date']),
            ],
            'keepEmptyRows' => true,
        ]);

        foreach ($response->getRows() as $row) {
            /** @var Row $row */
            $data[$row->getDimensionValues()[0]->getValue()] = [
                'date' => $row->getDimensionValues()[0]->getValue(),
                'revenue' => $row->getMetricValues()[0]->getValue(),
                'averageOrderValue' => $row->getMetricValues()[1]->getValue(),
            ];
        }

        return $data;
    }

    private function getDefaults(): array
    {
        $firstDay = Clock::now()->sub(new DateInterval('P7D'));
        $lastDay = Clock::now();
        // Today will not be included, as DatePeriod requires the DatePeriod::INCLUDE_END_DATE flag to be set.
        $period = new DatePeriod($firstDay, new DateInterval('P1D'), $lastDay);
        $defaults = [];

        foreach ($period as $date) {
            $defaults[$date->format('Ymd')] = [
                'date' => $date->format('Ymd'),
                'revenue' => '0',
                'averageOrderValue' => '0',
            ];
        }

        return $defaults;
    }
}
