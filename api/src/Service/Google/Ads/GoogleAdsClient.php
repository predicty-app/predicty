<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use App\Service\Clock\Clock;
use App\Service\Util\DateHelper;
use DateTimeImmutable;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsRow;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsServiceClient;

class GoogleAdsClient
{
    private const DATE_FORMAT = 'Y-m-d';
    private const MAX_PAGE_SIZE = 1000;

    public function __construct(private string $customerId, private GoogleAdsServiceClient $google)
    {
    }

    /**
     * @return iterable<array{id: string, name: string, type: string, ad_group_id: string, campaign_id: string}>
     */
    public function getAllAds(): iterable
    {
        $query = 'SELECT ad_group_ad.ad.id, campaign.id, ad_group.id, ad_group_ad.ad.name, ad_group_ad.ad.type FROM ad_group_ad';
        $response = $this->google->search($this->customerId, $query, ['pageSize' => self::MAX_PAGE_SIZE]);

        foreach ($response->iterateAllElements() as $row) {
            /** @var GoogleAdsRow $row */
            $name = (string) $row->getAdGroupAd()?->getAd()?->getName();

            if ($name === '') {
                $name = '(no name)';
            }

            yield [
                'id' => (string) $row->getAdGroupAd()?->getAd()?->getId(),
                'name' => $name,
                // @see https://developers.google.com/google-ads/api/reference/rpc/v13/AdTypeEnum.AdType
                'type' => (string) $row->getAdGroupAd()?->getAd()?->getType(),
                'ad_group_id' => (string) $row->getAdGroup()?->getId(),
                'campaign_id' => (string) $row->getCampaign()?->getId(),
            ];
        }
    }

    /**
     * @return iterable<array{id: string, name: string, campaign_id: string}>
     */
    public function getAllAdGroups(): iterable
    {
        $query = 'SELECT campaign.id, ad_group.id, ad_group.name FROM ad_group ORDER BY ad_group.id';
        $response = $this->google->search($this->customerId, $query, ['pageSize' => self::MAX_PAGE_SIZE]);

        foreach ($response->iterateAllElements() as $row) {
            /** @var GoogleAdsRow $row */
            yield [
                'id' => (string) $row->getAdGroup()?->getId(),
                'name' => (string) $row->getAdGroup()?->getName(),
                'campaign_id' => (string) $row->getCampaign()?->getId(),
            ];
        }
    }

    /**
     * @return iterable<array{id: string, name: string, start_date: ?DateTimeImmutable, end_date: ?DateTimeImmutable}>
     */
    public function getAllCampaigns(): iterable
    {
        $query = 'SELECT campaign.id, campaign.name, campaign.start_date, campaign.end_date FROM campaign ORDER BY campaign.id';
        $response = $this->google->search($this->customerId, $query, ['pageSize' => self::MAX_PAGE_SIZE]);

        foreach ($response->iterateAllElements() as $row) {
            /** @var GoogleAdsRow $row */
            yield [
                'id' => (string) $row->getCampaign()?->getId(),
                'name' => (string) $row->getCampaign()?->getName(),
                'start_date' => DateHelper::fromStringOrNull($row->getCampaign()?->getStartDate(), self::DATE_FORMAT),
                'end_date' => DateHelper::fromStringOrNull($row->getCampaign()?->getEndDate(), self::DATE_FORMAT),
            ];
        }
    }

    /**
     * @return iterable<array{ad_id: string, date: DateTimeImmutable, average_cpc: float, average_cpm: float, clicks: int, conversions: int, impressions: int, cost: float, all_conversions_value: float, cost_per_conversion: float, currency: string}>
     */
    public function getAdInsights(?DateTimeImmutable $since = null): iterable
    {
        $since ??= Clock::now();
        $to = $since->modify('-3 month');

        // https://developers.google.com/google-ads/api/fields/v14/ad_group_ad_query_builder
        $query = <<<'EOF'
            SELECT
                ad_group_ad.ad.id,
                segments.date,
                metrics.average_cpc,
                metrics.average_cpm,
                metrics.clicks,
                metrics.conversions,
                metrics.impressions,
                metrics.cost_micros,
                metrics.cost_per_conversion,
                metrics.all_conversions_value,
                customer.currency_code
            FROM ad_group_ad
            WHERE
                segments.date BETWEEN '%s' AND '%s'
            EOF;

        $response = $this->google->search(
            customerId: $this->customerId,
            query: sprintf($query, $since->format(self::DATE_FORMAT), $to->format(self::DATE_FORMAT)),
            optionalArgs: ['pageSize' => self::MAX_PAGE_SIZE]
        );

        foreach ($response->iterateAllElements() as $row) {
            /** @var GoogleAdsRow $row */
            $date = DateHelper::fromStringOrNull($row->getSegments()?->getDate(), self::DATE_FORMAT);

            if ($date === null) {
                continue;
            }

            $cpc = $this->fromMicro($row->getMetrics()?->getAverageCpc());
            $cpm = $this->fromMicro($row->getMetrics()?->getAverageCpm());
            $cost = $this->fromMicro($row->getMetrics()?->getCostMicros());
            $revenue = $this->fromMicro($row->getMetrics()?->getAllConversionsValue());
            $costPerConversion = $this->fromMicro($row->getMetrics()?->getCostPerConversion());

            yield [
                'ad_id' => (string) $row->getAdGroupAd()?->getAd()?->getId(),
                'date' => $date,
                'average_cpc' => $cpc,
                'average_cpm' => $cpm,
                'clicks' => (int) $row->getMetrics()?->getClicks(),
                'conversions' => (int) $row->getMetrics()?->getConversions(),
                'impressions' => (int) $row->getMetrics()?->getImpressions(),
                'cost' => $cost,
                'all_conversions_value' => $revenue,
                'cost_per_conversion' => $costPerConversion,
                'currency' => (string) $row->getCustomer()?->getCurrencyCode(),
            ];
        }
    }

    /**
     * @see https://groups.google.com/g/adwords-api/c/K4ux3hmlego
     */
    private function fromMicro(int|float|string|null $amount): float
    {
        $amount = (float) $amount;

        if ($amount === 0.0) {
            return 0.0;
        }

        return (float) bcdiv((string) $amount, '1000000', 2);
    }
}
