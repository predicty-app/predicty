<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use App\Entity\DataProvider;
use App\Service\DataImport\DataImportApi;
use App\Service\Util\MoneyHelper;
use Symfony\Component\Uid\Ulid;

class FacebookAdsUpdater
{
    public function __construct(
        private DataImportApi $dataImportApi,
        private FacebookAdsClientBuilder $facebookAdsClientBuilder
    ) {
    }

    public function update(Ulid $userId, Ulid $accountId, Ulid $connectedAccountId): void
    {
        $this->dataImportApi->setDefaultDataProvider(DataProvider::FACEBOOK_ADS);
        $client = $this->facebookAdsClientBuilder->build($connectedAccountId);

        foreach ($client->getAllCampaigns() as $campaign) {
            $this->dataImportApi->upsertCampaign($userId, $accountId, $campaign['name'], $campaign['id']);
        }

        $this->dataImportApi->flush();

        foreach ($client->getAllAdSets() as $adSet) {
            $campaign = $this->dataImportApi->getCampaignByExternalId($accountId, $adSet['campaign_id']);
            $this->dataImportApi->upsertAdSet($userId, $accountId, $campaign->getId(), $adSet['id'], $adSet['name'], $adSet['start_time'], $adSet['end_time']);
        }

        $this->dataImportApi->flush();

        $ads = $client->getAllAds();
        foreach ($ads as $ad) {
            $adSet = $this->dataImportApi->getAdSetByExternalId($accountId, $ad['adset_id']);
            $this->dataImportApi->upsertAd($userId, $accountId, $adSet->getCampaignId(), $adSet->getId(), $ad['id'], $ad['name'], $adSet->getStartedAt(), $adSet->getEndedAt());
        }

        $this->dataImportApi->flush();

        foreach ($ads as $ad) {
            foreach ($client->getAdInsights($ad['id']) as $stats) {
                $entity = $this->dataImportApi->getAdByExternalId($accountId, $ad['id']);
                $this->dataImportApi->upsertAdStats(
                    userId: $userId,
                    accountId: $accountId,
                    adId: $entity->getId(),
                    amountSpent: MoneyHelper::amount($stats['spend'], $stats['account_currency']),
                    date: $stats['date'],
                    conversions: $stats['conversions'],
                    clicks: $stats['clicks'],
                    impressions: $stats['impressions'],
                    leads: 0,
                    costPerClick: MoneyHelper::amount($stats['cpc'], $stats['account_currency']),
                    costPerResult: MoneyHelper::amount($stats['cost_per_conversion'], $stats['account_currency']),
                    costPerMil: MoneyHelper::amount($stats['cpm'], $stats['account_currency']),
                );
            }
            $this->dataImportApi->flush();
        }

        $this->dataImportApi->flush();
    }
}
