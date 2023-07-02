<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use App\Entity\DataProvider;
use App\Service\DataImport\DataImportApi;
use Brick\Money\Money;
use Symfony\Component\Uid\Ulid;

class GoogleAdsUpdater
{
    public function __construct(
        private DataImportApi $dataImportApi,
        private GoogleAdsClientBuilder $googleAdsClientFactory
    ) {
    }

    public function update(Ulid $userId, Ulid $accountId, Ulid $connectedAccountId): void
    {
        $googleAdsClient = $this->googleAdsClientFactory->build($connectedAccountId);
        $this->dataImportApi->setDefaultDataProvider(DataProvider::GOOGLE_ADS);

        foreach ($googleAdsClient->getAllCampaigns() as $campaign) {
            $this->dataImportApi->upsertCampaign($userId, $accountId, $campaign['name'], $campaign['id'], $campaign['start_date'], $campaign['end_date']);
        }

        $this->dataImportApi->flush();

        foreach ($googleAdsClient->getAllAdGroups() as $adset) {
            $campaign = $this->dataImportApi->getCampaignByExternalId($accountId, $adset['campaign_id']);
            $this->dataImportApi->upsertAdSet($userId, $accountId, $campaign->getId(), $adset['id'], $adset['name']);
        }

        $this->dataImportApi->flush();

        foreach ($googleAdsClient->getAllAds() as $ad) {
            $campaign = $this->dataImportApi->getCampaignByExternalId($accountId, $ad['campaign_id']);
            $adset = $this->dataImportApi->getAdSetByExternalId($accountId, $ad['ad_group_id']);
            $this->dataImportApi->upsertAd($userId, $accountId, $campaign->getId(), $adset->getId(), $ad['id'], $ad['name']);
        }

        $this->dataImportApi->flush();

        $persistedCount = 0;
        foreach ($googleAdsClient->getAdInsights() as $adInsight) {
            $ad = $this->dataImportApi->getAdByExternalId($accountId, $adInsight['ad_id']);
            $currency = $adInsight['currency'];

            $this->dataImportApi->upsertAdInsights(
                userId: $userId,
                accountId: $accountId,
                adId: $ad->getId(),
                amountSpent: Money::of($adInsight['cost'], $currency),
                date: $adInsight['date'],
                conversions: $adInsight['conversions'],
                clicks: $adInsight['clicks'],
                impressions: $adInsight['impressions'],
                leads: 0, // there is no metric that represents leads
                costPerClick: Money::of($adInsight['average_cpc'], $currency),
                costPerResult: Money::of($adInsight['cost_per_conversion'], $currency),
                costPerMil: Money::of($adInsight['average_cpm'], $currency),
            );

            ++$persistedCount;

            if ($persistedCount > 100) {
                $this->dataImportApi->flush();
                $persistedCount = 0;
            }
        }

        $this->dataImportApi->flush();
    }
}
