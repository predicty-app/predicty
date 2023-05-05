<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\DailyRevenue;
use App\Entity\Importable;
use App\Entity\ImportResult;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use App\Repository\AdStatsRepository;
use App\Repository\CampaignRepository;
use App\Repository\DailyRevenueRepository;
use Brick\Money\Money;
use DateTimeImmutable;
use Exception;

class DefaultDataImportApi implements DataImportApi, TrackableDataImportApi
{
    private ImportResult $importResult;
    private ?int $importId = null;

    public function __construct(
        private CampaignRepository $campaignRepository,
        private AdSetRepository $adSetRepository,
        private AdRepository $adRepository,
        private AdStatsRepository $adStatsRepository,
        private DailyRevenueRepository $dailyRevenueRepository
    ) {
        $this->importResult = new ImportResult();
    }

    public function getOrCreateCampaign(int $userId, int $accountId, string $name, string $externalId): Campaign
    {
        $entity = $this->campaignRepository->findByUserIdAndExternalId($userId, $externalId);

        if ($entity === null) {
            $entity = new Campaign($externalId, $userId, $accountId, $name);

            $this->doTrack($entity);
            $this->campaignRepository->save($entity);
        }

        return $entity;
    }

    public function getOrCreateDailyRevenue(int $userId, int $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue
    {
        $entity = $this->dailyRevenueRepository->findByDay($userId, $date);

        if ($entity === null) {
            $entity = new DailyRevenue($userId, $accountId, $date, $revenue, $averageOrderValue);

            $this->doTrack($entity);
            $this->dailyRevenueRepository->save($entity);
        }

        return $entity;
    }

    public function createDailyRevenueIfNotExists(int $userId, int $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): ?DailyRevenue
    {
        $entity = $this->dailyRevenueRepository->findByDay($userId, $date);

        if ($entity === null) {
            $entity = new DailyRevenue($userId, $accountId, $date, $revenue, $averageOrderValue);

            $this->doTrack($entity);
            $this->dailyRevenueRepository->save($entity);
        }

        return $entity;
    }

    public function getOrCreateAd(AdSet $adSet, string $name, string $externalId): Ad
    {
        $entity = $this->adRepository->findByUserIdAndExternalId($adSet->getUserId(), $externalId);

        if ($entity === null) {
            $entity = new Ad(
                userId: $adSet->getUserId(),
                accountId: $adSet->getAccountId(),
                externalId: $externalId,
                campaignId: $adSet->getCampaignId(),
                name: $name,
                adSetId: $adSet->getId(),
            );

            $this->doTrack($entity);
            $this->adRepository->save($entity);
        }

        return $entity;
    }

    public function getOrCreateAdSet(Campaign $campaign, string $name, string $externalId): AdSet
    {
        $entity = $this->adSetRepository->findByCampaignIdAndExternalId($campaign->getUserId(), $campaign->getId(), $externalId);

        if ($entity === null) {
            $entity = new AdSet(
                externalId: $externalId,
                userId: $campaign->getUserId(),
                accountId: $campaign->getAccountId(),
                campaignId: $campaign->getId(),
                name: $name,
            );

            $this->doTrack($entity);
            $this->adSetRepository->save($entity);
        }

        return $entity;
    }

    public function getOrCreateAdStats(Ad $ad, DateTimeImmutable $date, int $results, Money $costPerResult, Money $amountSpent): AdStats
    {
        $entity = $this->adStatsRepository->findByAdIdAndDay($ad->getId(), $date);

        if ($entity === null) {
            $entity = new AdStats(
                userId: $ad->getUserId(),
                accountId: $ad->getAccountId(),
                adId: $ad->getId(),
                results: $results,
                costPerResult: $costPerResult,
                amountSpent: $amountSpent,
                date: $date
            );

            $this->doTrack($entity);
            $this->adStatsRepository->save($entity);
        }

        return $entity;
    }

    public function track(int $importId, ImportResult $importResult = null): void
    {
        $this->importId = $importId;
        $this->importResult = $importResult ?? new ImportResult();
    }

    public function getImportResult(): ImportResult
    {
        return $this->importResult;
    }

    private function doTrack(Importable $importable): void
    {
        if ($this->importId !== null) {
            $importable->setImportId($this->importId);

            match (true) {
                $importable instanceof Campaign => $this->importResult->incrementCreatedCampaigns(),
                $importable instanceof AdSet => $this->importResult->incrementCreatedAdSets(),
                $importable instanceof Ad => $this->importResult->incrementCreatedAds(),
                $importable instanceof AdStats => $this->importResult->incrementCreatedAdStats(),
                $importable instanceof DailyRevenue => $this->importResult->incrementCreatedDailyRevenues(),
                default => throw new Exception('Unknown entity type: '.$importable::class),
            };
        }
    }
}
