<?php

declare(strict_types=1);

namespace App\Service\DataRecalculation;

use App\Repository\AdCollectionRepository;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use App\Repository\AdStatsRepository;
use App\Repository\CampaignRepository;

/**
 * Recalculates start and end dates for entities like Ad, AdSet, Campaign and AdCollection.
 */
class StartAndEndDateRecalculationService
{
    public function __construct(
        private AdRepository $adRepository,
        private AdStatsRepository $adStatsRepository,
        private AdSetRepository $adSetRepository,
        private AdCollectionRepository $adCollectionRepository,
        private CampaignRepository $campaignRepository,
    ) {
    }

    public function recalculate(int $userId): void
    {
        $this->recalculateAds($userId);
        $this->recalculateAdSets($userId);
        $this->recalculateCampaigns($userId);
        $this->recalculateCampaigns($userId);
        $this->recalculateAdCollections($userId);
    }

    private function recalculateAds(int $accountId): void
    {
        $ads = $this->adRepository->findAllByAccountId($accountId);

        foreach ($ads as $ad) {
            $dates = $this->adStatsRepository->findStartAndEndDateForAnAd($ad->getId());

            if ($dates['start'] !== null) {
                $ad->setStartedAt($dates['start']);
            }

            if ($dates['end'] !== null) {
                $ad->setEndedAt($dates['end']);
            }

            $this->adRepository->save($ad);
        }
    }

    private function recalculateAdSets(int $accountId): void
    {
        $adSets = $this->adSetRepository->findAllByAccountId($accountId);

        foreach ($adSets as $adSet) {
            $dates = $this->adStatsRepository->findStartAndEndDateForAnAdSet($adSet->getId());

            if ($dates['start'] !== null) {
                $adSet->setStartedAt($dates['start']);
            }

            if ($dates['end'] !== null) {
                $adSet->setEndedAt($dates['end']);
            }

            $this->adSetRepository->save($adSet);
        }
    }

    private function recalculateCampaigns(int $userId): void
    {
        $campaigns = $this->campaignRepository->findAllByAccountId($userId);

        foreach ($campaigns as $campaign) {
            $dates = $this->adStatsRepository->findStartAndEndDateForACampaign($campaign->getId());

            if ($dates['start'] !== null) {
                $campaign->setStartedAt($dates['start']);
            }

            if ($dates['end'] !== null) {
                $campaign->setEndedAt($dates['end']);
            }

            $this->campaignRepository->save($campaign);
        }
    }

    private function recalculateAdCollections(int $userId): void
    {
        $adCollections = $this->adCollectionRepository->findAllByAccountId($userId);

        foreach ($adCollections as $adCollection) {
            $dates = $this->adStatsRepository->findStartAndEndDateForAnAdCollection($adCollection->getAdsIds());

            if ($dates['start'] !== null) {
                $adCollection->setStartedAt($dates['start']);
            }

            if ($dates['end'] !== null) {
                $adCollection->setEndedAt($dates['end']);
            }

            $this->adCollectionRepository->save($adCollection);
        }
    }
}
