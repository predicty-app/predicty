<?php

declare(strict_types=1);

namespace App\Service\Google\Analytics;

use App\Entity\DataProvider;
use App\Service\Security\ConnectedAccountCredentials\ConnectedAccountCredentialsProvider;
use Google\Client as GoogleClient;
use Google\Service\Analytics;
use Google\Service\AnalyticsReporting;

/**
 * @deprecated
 */
class GoogleAnalyticsUAApi
{
    private AnalyticsReporting $analyticsReporting;

    public function __construct(
        private GoogleClient $client,
        private ConnectedAccountCredentialsProvider $dataProviderCredentialsProvider,
    ) {
        $this->client->addScope(Analytics::ANALYTICS_READONLY);
        $this->analyticsReporting = new AnalyticsReporting($this->client);
    }

    /**
     * @return iterable<array{date: string, revenue: string, averageOrderValue: string}>
     */
    public function getDailyRevenue(int $userId, string $viewId): iterable
    {
        $this->authenticate($userId);

        $reportRequest = new AnalyticsReporting\ReportRequest();
        $reportRequest->setViewId($viewId);
        $reportRequest->setIncludeEmptyRows(true);

        $reportRequest->setDateRanges([
            new AnalyticsReporting\DateRange([
                'startDate' => '7daysAgo',
                'endDate' => 'yesterday',
            ]),
        ]);
        $reportRequest->setMetrics([
            new AnalyticsReporting\Metric([
                'expression' => 'ga:transactionRevenue',
            ]),
            new AnalyticsReporting\Metric([
                'expression' => 'ga:revenuePerTransaction',
            ]),
        ]);

        $reportRequest->setDimensions([
            new AnalyticsReporting\Dimension([
                'name' => 'ga:date',
            ]),
        ]);

        $body = new AnalyticsReporting\GetReportsRequest();
        $body->setReportRequests([$reportRequest]);
        $response = $this->analyticsReporting->reports->batchGet($body);
        $reportData = $response->getReports()[0]->getData();

        foreach ($reportData->getRows() as $row) {
            $metrics = $row->getMetrics();
            $dimensions = $row->getDimensions();

            $date = $dimensions[0];
            $transactionRevenue = $metrics[0]->getValues()[0];
            $revenuePerTransaction = $metrics[0]->getValues()[1];

            yield [
                'date' => $date,
                'revenue' => $transactionRevenue,
                'averageOrderValue' => $revenuePerTransaction,
            ];
        }
    }

    private function authenticate(int $userId): void
    {
        $credentials = $this->dataProviderCredentialsProvider->getCredentials($userId, DataProvider::GOOGLE_ANALYTICS);
        $this->client->fetchAccessTokenWithRefreshToken($credentials->getCredentials()['token']);
    }
}
