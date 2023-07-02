<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Google\Ads;

use App\Service\Google\Ads\GoogleAdsClient;
use App\Service\Util\DateHelper;
use Google\Ads\GoogleAds\V12\Common\Metrics;
use Google\Ads\GoogleAds\V12\Common\Segments;
use Google\Ads\GoogleAds\V12\Resources\Ad;
use Google\Ads\GoogleAds\V12\Resources\AdGroup;
use Google\Ads\GoogleAds\V12\Resources\AdGroupAd;
use Google\Ads\GoogleAds\V12\Resources\Campaign;
use Google\Ads\GoogleAds\V12\Resources\Customer;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsRow;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsServiceClient;
use Google\ApiCore\PagedListResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\Google\Ads\GoogleAdsClient
 */
class GoogleAdsClientTest extends TestCase
{
    public function test_get_ad_insights(): void
    {
        $ad = new Ad([
            'id' => 1234567890,
            'name' => 'Test',
            'type' => 1,
        ]);
        $segments = new Segments(['date' => '2023-06-23']);
        $customer = new Customer(['currency_code' => 'EUR']);

        $metrics = new Metrics([
            'average_cpc' => 1230000,
            'average_cpm' => 79234567,
            'clicks' => 12345,
            'conversions' => 123,
            'impressions' => 890,
            'cost_micros' => 523000000,
            'all_conversions_value' => 123000000,
            'cost_per_conversion' => 423000000,
        ]);

        $adGroup = new AdGroup(['id' => 1234567890]);
        $adGroupAd = new AdGroupAd(['ad' => $ad]);
        $campaign = new Campaign(['id' => 9234567898]);
        $row = new GoogleAdsRow([
            'ad_group_ad' => $adGroupAd,
            'campaign' => $campaign,
            'ad_group' => $adGroup,
            'metrics' => $metrics,
            'segments' => $segments,
            'customer' => $customer,
        ]);

        $response = $this->createMock(PagedListResponse::class);
        $response->method('iterateAllElements')->willReturn([$row]);

        $google = $this->createMock(GoogleAdsServiceClient::class);
        $google->method('search')->willReturn($response);

        $expected = [[
            'ad_id' => '1234567890',
            'date' => DateHelper::fromString('2023-06-23')->setTime(0, 0, 0),
            'average_cpc' => 1.23,
            'average_cpm' => 79.23,
            'clicks' => 12345,
            'conversions' => 123,
            'impressions' => 890,
            'cost' => 523.0,
            'all_conversions_value' => 123.0,
            'currency' => 'EUR',
            'cost_per_conversion' => 423.0,
        ]];

        $client = new GoogleAdsClient('123', $google);
        $result = [...$client->getAdInsights()];
        $this->assertEquals($expected, $result);
    }

    public function test_get_all_ad_groups(): void
    {
        $adGroup = new AdGroup(['id' => 1234567890, 'name' => 'Test AdGroup']);
        $campaign = new Campaign(['id' => 9234567898]);
        $row = new GoogleAdsRow(['campaign' => $campaign, 'ad_group' => $adGroup]);

        $response = $this->createMock(PagedListResponse::class);
        $response->method('iterateAllElements')->willReturn([$row]);

        $google = $this->createMock(GoogleAdsServiceClient::class);
        $google->method('search')->willReturn($response);

        $expected = [[
            'id' => '1234567890',
            'name' => 'Test AdGroup',
            'campaign_id' => '9234567898',
        ]];

        $client = new GoogleAdsClient('123', $google);
        $result = [...$client->getAllAdGroups()];
        $this->assertEquals($expected, $result);
    }

    public function test_get_all_ads(): void
    {
        $ad = new Ad([
            'id' => 1234567890,
            'name' => 'Test',
            'type' => 1,
        ]);

        $adGroup = new AdGroup(['id' => 1234567890]);
        $adGroupAd = new AdGroupAd(['ad' => $ad]);
        $campaign = new Campaign(['id' => 9234567898]);
        $row = new GoogleAdsRow(['ad_group_ad' => $adGroupAd, 'campaign' => $campaign, 'ad_group' => $adGroup]);

        $response = $this->createMock(PagedListResponse::class);
        $response->method('iterateAllElements')->willReturn([$row]);

        $google = $this->createMock(GoogleAdsServiceClient::class);
        $google->method('search')->willReturn($response);

        $expected = [[
            'id' => '1234567890',
            'name' => 'Test',
            'type' => '1',
            'ad_group_id' => '1234567890',
            'campaign_id' => '9234567898',
        ]];

        $client = new GoogleAdsClient('123', $google);
        $result = [...$client->getAllAds()];
        $this->assertEquals($expected, $result);
    }

    public function test_get_all_campaigns(): void
    {
        $campaign = new Campaign([
            'id' => 9234567898,
            'name' => 'Test Campaign',
            'start_date' => '2020-01-01',
            'end_date' => '2020-01-01',
        ]);

        $row = new GoogleAdsRow(['campaign' => $campaign]);

        $response = $this->createMock(PagedListResponse::class);
        $response->method('iterateAllElements')->willReturn([$row]);

        $google = $this->createMock(GoogleAdsServiceClient::class);
        $google->method('search')->willReturn($response);

        $expectedDate = DateHelper::fromString('2020-01-01', 'Y-m-d')->setTime(0, 0, 0);
        $expected = [[
            'id' => '9234567898',
            'name' => 'Test Campaign',
            'start_date' => $expectedDate,
            'end_date' => $expectedDate,
        ]];

        $client = new GoogleAdsClient('123', $google);
        $result = [...$client->getAllCampaigns()];
        $this->assertEquals($expected, $result);
    }
}
