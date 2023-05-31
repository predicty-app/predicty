<?php

declare(strict_types=1);

namespace App\Service\DataRecalculation;

use App\Repository\AdCollectionRepository;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use App\Repository\AdStatsRepository;
use App\Repository\CampaignRepository;
use Symfony\Component\Uid\Ulid;

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

    public function recalculate(Ulid $accountId): void
    {
        $this->recalculateAds($accountId);
        $this->recalculateAdSets($accountId);
        $this->recalculateCampaigns($accountId);
        $this->recalculateCampaigns($accountId);
        $this->recalculateAdCollections($accountId);
    }

    private function recalculateAds(Ulid $accountId): void
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

    private function recalculateAdSets(Ulid $accountId): void
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

    private function recalculateCampaigns(Ulid $accountId): void
    {
        $campaigns = $this->campaignRepository->findAllByAccountId($accountId);

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

    private function recalculateAdCollections(Ulid $accountId): void
    {
        $adCollections = $this->adCollectionRepository->findAllByAccountId($accountId);

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
