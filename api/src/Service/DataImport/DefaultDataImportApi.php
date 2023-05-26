<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\DailyRevenue;
use App\Entity\DataProvider;
use App\Entity\Importable;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use App\Repository\AdStatsRepository;
use App\Repository\CampaignRepository;
use App\Repository\DailyRevenueRepository;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class DefaultDataImportApi implements DataImportApi
{
    /**
     * @var null|callable
     */
    private mixed $onPersist = null;
    private DataProvider $dataProvider = DataProvider::OTHER;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CampaignRepository $campaignRepository,
        private AdSetRepository $adSetRepository,
        private AdRepository $adRepository,
        private AdStatsRepository $adStatsRepository,
        private DailyRevenueRepository $dailyRevenueRepository,
    ) {
    }

    public function setOnPersistCallback(callable $callback): void
    {
        $this->onPersist = $callback;
    }

    public function setDefaultDataProvider(DataProvider $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function getCampaignByExternalId(Ulid $accountId, string $externalId): Campaign
    {
        return $this->campaignRepository->findByAccountIdAndExternalId($accountId, $externalId) ?? throw new RuntimeException('Campaign not found');
    }

    public function getAdSetByExternalId(Ulid $accountId, string $externalId): AdSet
    {
        return $this->adSetRepository->findByAccountIdAndExternalId($accountId, $externalId) ?? throw new RuntimeException('AdSet not found');
    }

    public function getAdByExternalId(Ulid $accountId, string $externalId): Ad
    {
        return $this->adRepository->findByAccountIdAndExternalId($accountId, $externalId) ?? throw new RuntimeException('Ad not found');
    }

    public function upsertCampaign(Ulid $userId, Ulid $accountId, string $name, string $externalId, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Campaign
    {
        $entity = $this->campaignRepository->findByAccountIdAndExternalId($accountId, $externalId);

        if ($entity === null) {
            $entity = new Campaign(new Ulid(), $userId, $accountId, $externalId, $name, $this->dataProvider);
        }

        $entity->setName($name);
        $entity->setStartedAt($startedAt);
        $entity->setEndedAt($startedAt);

        $this->persist($entity);

        return $entity;
    }

    public function upsertAdSet(Ulid $userId, Ulid $accountId, Ulid $campaignId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): AdSet
    {
        $entity = $this->adSetRepository->findByAccountIdAndExternalId($accountId, $externalId);

        if ($entity === null) {
            $entity = new AdSet(new Ulid(), $userId, $accountId, $campaignId, $externalId, $name, $this->dataProvider);
        }

        $entity->setName($name);
        $entity->setStartedAt($startedAt);
        $entity->setEndedAt($endedAt);

        $this->persist($entity);

        return $entity;
    }

    public function upsertAd(Ulid $userId, Ulid $accountId, Ulid $campaignId, Ulid $adSetId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Ad
    {
        $entity = $this->adRepository->findByAccountIdAndExternalId($accountId, $externalId);

        if ($entity === null) {
            $entity = new Ad(new Ulid(), $userId, $accountId, $campaignId, $externalId, $name, $adSetId, $this->dataProvider);
        }

        $entity->setName($name);
        $entity->setStartedAt($startedAt);
        $entity->setEndedAt($endedAt);

        $this->persist($entity);

        return $entity;
    }

    public function upsertAdStats(Ulid $userId, Ulid $accountId, Ulid $adId, int $results, Money $costPerResult, Money $amountSpent, DateTimeImmutable $date): AdStats
    {
        $entity = $this->adStatsRepository->findByAdIdAndDay($adId, $date);

        if ($entity === null) {
            $entity = new AdStats(new Ulid(), $userId, $accountId, $adId, $results, $costPerResult, $amountSpent, $date);
        }

        $entity->setResults($results);
        $entity->setCostPerResult($costPerResult);
        $entity->setAmountSpent($amountSpent);

        $this->persist($entity);

        return $entity;
    }

    public function upsertDailyRevenue(Ulid $userId, Ulid $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue
    {
        $entity = $this->dailyRevenueRepository->findByDay($accountId, $date);

        if ($entity === null) {
            $entity = new DailyRevenue(new Ulid(), $userId, $accountId, $date, $revenue, $averageOrderValue);
        }

        $entity->setRevenue($revenue);
        $entity->setAverageOrderValue($averageOrderValue);

        $this->persist($entity);

        return $entity;
    }

    private function persist(Importable $importable): void
    {
        if ($this->onPersist !== null) {
            ($this->onPersist)($importable);
        }

        $this->entityManager->persist($importable);
    }
}
