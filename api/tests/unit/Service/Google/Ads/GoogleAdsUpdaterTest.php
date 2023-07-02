<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Google\Ads;

use App\Entity\Ad;
use App\Entity\Campaign;
use App\Entity\DataProvider;
use App\Service\DataImport\DataImportApi;
use App\Service\Google\Ads\GoogleAdsClient;
use App\Service\Google\Ads\GoogleAdsClientBuilder;
use App\Service\Google\Ads\GoogleAdsUpdater;
use App\Service\Util\DateHelper;
use Brick\Money\Money;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Google\Ads\GoogleAdsUpdater
 */
class GoogleAdsUpdaterTest extends TestCase
{
    public function test_update_sets_default_data_provider(): void
    {
        $googleAdsClientBuilder = $this->createMock(GoogleAdsClientBuilder::class);
        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('setDefaultDataProvider')->with(DataProvider::GOOGLE_ADS);

        $updater = new GoogleAdsUpdater(
            $dataImportApi,
            $googleAdsClientBuilder
        );

        $updater->update(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NPQ3ZW19C4WNHSATPCJFKJ'),
        );
    }

    public function test_updates_campaigns_data(): void
    {
        $client = $this->createMock(GoogleAdsClient::class);
        $client->expects($this->once())->method('getAllCampaigns')->willReturn([
            [
                'name' => 'Campaign 1',
                'id' => '123456',
                'start_date' => DateHelper::fromString('2021-01-01'),
                'end_date' => DateHelper::fromString('2021-01-31'),
            ],
        ]);

        $googleAdsClientBuilder = $this->createMock(GoogleAdsClientBuilder::class);
        $googleAdsClientBuilder->expects($this->once())->method('build')->willReturn($client);

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('upsertCampaign')->with(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            'Campaign 1',
            '123456',
            DateHelper::fromString('2021-01-01'),
            DateHelper::fromString('2021-01-31')
        );

        $updater = new GoogleAdsUpdater(
            $dataImportApi,
            $googleAdsClientBuilder
        );

        $updater->update(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NPQ3ZW19C4WNHSATPCJFKJ'),
        );
    }

    public function test_update_updates_adgroups_data(): void
    {
        $client = $this->createMock(GoogleAdsClient::class);
        $client->expects($this->once())->method('getAllAdGroups')->willReturn([
            [
                'name' => 'AdGroup 1',
                'id' => '123456',
                'campaign_id' => '654321',
            ],
        ]);

        $googleAdsClientBuilder = $this->createMock(GoogleAdsClientBuilder::class);
        $googleAdsClientBuilder->expects($this->once())->method('build')->willReturn($client);

        $campaign = $this->createMock(Campaign::class);
        $campaign->expects($this->once())->method('getId')->willReturn(Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getCampaignByExternalId')->willReturn($campaign);

        $dataImportApi->expects($this->once())->method('upsertAdSet')->with(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'),
            '123456',
            'AdGroup 1',
        );

        $updater = new GoogleAdsUpdater(
            $dataImportApi,
            $googleAdsClientBuilder
        );

        $updater->update(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NPQ3ZW19C4WNHSATPCJFKJ'),
        );
    }

    public function test_updates_ads_data(): void
    {
        $client = $this->createMock(GoogleAdsClient::class);
        $client->expects($this->once())->method('getAllAdGroups')->willReturn([
            [
                'name' => 'AdGroup 1',
                'id' => '123456',
                'campaign_id' => '654321',
            ],
        ]);

        $googleAdsClientBuilder = $this->createMock(GoogleAdsClientBuilder::class);
        $googleAdsClientBuilder->expects($this->once())->method('build')->willReturn($client);

        $campaign = $this->createMock(Campaign::class);
        $campaign->expects($this->once())->method('getId')->willReturn(Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getCampaignByExternalId')->willReturn($campaign);

        $dataImportApi->expects($this->once())->method('upsertAdSet')->with(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'),
            '123456',
            'AdGroup 1',
        );

        $updater = new GoogleAdsUpdater(
            $dataImportApi,
            $googleAdsClientBuilder
        );

        $updater->update(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NPQ3ZW19C4WNHSATPCJFKJ'),
        );
    }

    public function test_updates_ad_insights_data(): void
    {
        $client = $this->createMock(GoogleAdsClient::class);
        $client->expects($this->once())->method('getAdInsights')->willReturn([
            [
                'ad_id' => '123456',
                'date' => DateHelper::fromString('2021-01-01', 'Y-m-d'),
                'average_cpc' => 0.1,
                'average_cpm' => 0.2,
                'clicks' => 355,
                'conversions' => 2,
                'impressions' => 900,
                'cost' => 4,
                'all_conversions_value' => 5,
                'cost_per_conversion' => 6,
                'currency' => 'EUR',
            ],
        ]);

        $googleAdsClientBuilder = $this->createMock(GoogleAdsClientBuilder::class);
        $googleAdsClientBuilder->expects($this->once())->method('build')->willReturn($client);

        $ad = $this->createMock(Ad::class);
        $ad->expects($this->once())->method('getId')->willReturn(Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'));

        $dataImportApi = $this->createMock(DataImportApi::class);
        $dataImportApi->expects($this->once())->method('getAdByExternalId')->willReturn($ad);

        $dataImportApi->expects($this->once())->method('upsertAdInsights')->with(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NR8N4TMCRJQDDHXYQD1DN9'),
            Money::of(4, 'EUR'),
            DateHelper::fromString('2021-01-01', 'Y-m-d'),
            2,
            355,
            900,
            0,
            Money::of(0.1, 'EUR'),
            Money::of(6, 'EUR'),
            Money::of(0.2, 'EUR'),
        );

        $updater = new GoogleAdsUpdater(
            $dataImportApi,
            $googleAdsClientBuilder
        );

        $updater->update(
            Ulid::fromString('01H4NPPPPBCRPN01NDCGTNPZFX'),
            Ulid::fromString('01H4NPPZ1YJSN0XKT6VFY79MDW'),
            Ulid::fromString('01H4NPQ3ZW19C4WNHSATPCJFKJ'),
        );
    }
}
