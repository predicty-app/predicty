<?php

/** @noinspection PhpUndefinedFieldInspection */

declare(strict_types=1);

namespace App\Service\Facebook;

use App\Service\Clock\Clock;
use App\Service\Util\DateHelper;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use FacebookAds\Api as FacebookApi;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;

class FacebookAdsClient
{
    public function __construct(private FacebookApi $api, private string $adAccountId)
    {
    }

    /**
     * @return iterable<array{id: string, name: string, start_time: ?DateTimeImmutable, stop_time: ?DateTimeImmutable}>
     */
    public function getAllCampaigns(): iterable
    {
        $account = new AdAccount('act_'.$this->adAccountId);
        $fields = [
            'id',
            'name',
            'account_id',
            'start_time',
            'stop_time',
        ];

        $campaigns = $account->getCampaigns($fields);
        while ($campaigns->getNext() !== null) {
            $campaigns->fetchAfter();
        }

        $results = [];
        foreach ($campaigns as $campaign) {
            $results[] = [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'start_time' => DateHelper::fromStringOrNull($campaign->start_time, DateTimeInterface::ATOM),
                'stop_time' => DateHelper::fromStringOrNull($campaign->stop_time, DateTimeInterface::ATOM),
            ];
        }

        return $results;
    }

    /**
     * @return iterable<array{id: string, name: string, campaign_id: string, start_time: ?DateTimeImmutable, end_time: ?DateTimeImmutable}>
     */
    public function getAllAdSets(): iterable
    {
        $account = new AdAccount('act_'.$this->adAccountId);
        $fields = [
            AdSetFields::ID,
            AdSetFields::NAME,
            AdSetFields::CAMPAIGN_ID,
            AdSetFields::START_TIME,
            AdSetFields::END_TIME,
        ];

        $adSets = $account->getAdSets($fields);
        while ($adSets->getNext() !== null) {
            $adSets->fetchAfter();
        }

        $results = [];
        foreach ($adSets as $adSet) {
            $results[] = [
                'id' => $adSet->id,
                'name' => $adSet->name,
                'campaign_id' => $adSet->campaign_id,
                'start_time' => DateHelper::fromStringOrNull($adSet->start_time, DateTimeInterface::ATOM),
                'end_time' => DateHelper::fromStringOrNull($adSet->end_time, DateTimeInterface::ATOM),
            ];
        }

        return $results;
    }

    /**
     * @return iterable<array{id: string, name: string, adset_id: string}>
     */
    public function getAllAds(): iterable
    {
        $account = new AdAccount('act_'.$this->adAccountId);
        $fields = [
            'id',
            'name',
            'adset_id',
        ];

        $ads = $account->getAds($fields);
        while ($ads->getNext() !== null) {
            $ads->fetchAfter();
        }

        $results = [];
        foreach ($ads as $ad) {
            $results[] = ['id' => $ad->id, 'name' => $ad->name, 'adset_id' => $ad->adset_id];
        }

        return $results;
    }

    /**
     * @return array<array{ad_id: string, account_currency: string, clicks: int, spend: float, impressions: int, cost_per_conversion: float, conversions: int, date: DateTimeImmutable}>
     */
    public function getAdInsights(string $adId): array
    {
        $since = Clock::now()->sub(new DateInterval('P90D'));
        $to = Clock::now();
        $period = new DatePeriod($since, new DateInterval('P1D'), $to, 90);

        $data = [];
        foreach ($period as $date) {
            $data[$date->format('Y-m-d')] = [
                'ad_id' => $adId,
                'clicks' => 0,
                'impressions' => 0,
                'cost_per_conversion' => 0.0,
                'conversions' => 0,
                'spend' => 0.0,
                'account_currency' => 'PLN',
                'date' => $date,
            ];
        }

        $fields = [
            'ad_id',
            'account_currency',
            'clicks',
            'spend',
            'impressions',
            'cost_per_conversion',
            'conversions',
        ];

        $params = [
            'action_breakdowns' => 'action_type',
            'action_report_time' => 'conversion',
            'date_preset' => 'last_90d',
            'time_increment' => 1,
            'action_attribution_windows' => '1d_click,7d_click,1d_view',
            'time_range' => ['since' => $since->format('Y-m-d'), 'until' => $to->format('Y-m-d')],
        ];

        $ad = new Ad(id: $adId, api: $this->api);
        $insights = $ad->getInsights($fields, $params);
        while ($insights->getNext() !== null) {
            $insights->fetchAfter();
        }

        foreach ($insights as $insight) {
            /* @var $insight AdsInsights */

            $numberOfConversions = FacebookInsightsConversionCounter::count($insight);
            $spend = (float) $insight->spend;
            $costPerConversion = 0;

            if ($numberOfConversions > 0) {
                $costPerConversion = (float) bcdiv((string) $spend, (string) $numberOfConversions, 2);
            }

            $data[$insight->date_start] = [
                'ad_id' => $insight->ad_id,
                'clicks' => (int) $insight->clicks,
                'impressions' => (int) $insight->impressions,
                'cost_per_conversion' => $costPerConversion,
                'conversions' => $numberOfConversions,
                'spend' => $spend,
                'account_currency' => $insight->account_currency,
                'date' => DateHelper::fromString($insight->date_start, 'Y-m-d'),
            ];
        }

        return $data;
    }
}
