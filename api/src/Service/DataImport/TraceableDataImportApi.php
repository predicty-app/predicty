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
use App\Entity\ImportResult;
use Brick\Money\Money;
use DateTimeImmutable;
use Symfony\Component\Uid\Ulid;

class TraceableDataImportApi implements DataImportApi
{
    private ?Ulid $importId;
    private ImportResult $importResult;

    public function __construct(private DataImportApi $dataImportApi)
    {
        $this->importId = null;
        $this->importResult = new ImportResult();
        $this->setOnPersistCallback(fn (Importable $importable) => $this->onPersistHandler($importable));
    }

    public function trace(Ulid $importId, ImportResult $importResult): void
    {
        $this->importId = $importId;
        $this->importResult = $importResult;
    }

    public function setDefaultDataProvider(DataProvider $dataProvider): void
    {
        $this->dataImportApi->setDefaultDataProvider($dataProvider);
    }

    public function setOnPersistCallback(callable $callback): void
    {
        $this->dataImportApi->setOnPersistCallback($callback);
    }

    public function flush(): void
    {
        $this->dataImportApi->flush();
    }

    public function getCampaignByExternalId(Ulid $accountId, string $externalId): Campaign
    {
        return $this->dataImportApi->getCampaignByExternalId($accountId, $externalId);
    }

    public function getAdSetByExternalId(Ulid $accountId, string $externalId): AdSet
    {
        return $this->dataImportApi->getAdSetByExternalId($accountId, $externalId);
    }

    public function getAdByExternalId(Ulid $accountId, string $externalId): Ad
    {
        return $this->dataImportApi->getAdByExternalId($accountId, $externalId);
    }

    public function upsertCampaign(Ulid $userId, Ulid $accountId, string $name, string $externalId, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Campaign
    {
        return $this->dataImportApi->upsertCampaign($userId, $accountId, $name, $externalId, $startedAt, $endedAt);
    }

    public function upsertAdSet(Ulid $userId, Ulid $accountId, Ulid $campaignId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): AdSet
    {
        return $this->dataImportApi->upsertAdSet($userId, $accountId, $campaignId, $externalId, $name, $startedAt, $endedAt);
    }

    public function upsertAd(Ulid $userId, Ulid $accountId, Ulid $campaignId, Ulid $adSetId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Ad
    {
        return $this->dataImportApi->upsertAd($userId, $accountId, $campaignId, $adSetId, $externalId, $name, $startedAt, $endedAt);
    }

    public function upsertAdStats(Ulid $userId, Ulid $accountId, Ulid $adId, int $results, Money $costPerResult, Money $amountSpent, DateTimeImmutable $date): AdStats
    {
        return $this->dataImportApi->upsertAdStats($userId, $accountId, $adId, $results, $costPerResult, $amountSpent, $date);
    }

    public function upsertDailyRevenue(Ulid $userId, Ulid $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue
    {
        return $this->dataImportApi->upsertDailyRevenue($userId, $accountId, $date, $revenue, $averageOrderValue);
    }

    private function onPersistHandler(Importable $importable): void
    {
        if ($this->importId !== null) {
            $importable->setImportId($this->importId);
        }

        $this->incrementStats($importable);
    }

    private function incrementStats(Importable $importable): void
    {
        // @todo check if entity is new or just changed
        match (true) {
            $importable instanceof Campaign => $this->importResult->incrementCreatedCampaigns(),
            $importable instanceof AdSet => $this->importResult->incrementCreatedAdSets(),
            $importable instanceof Ad => $this->importResult->incrementCreatedAds(),
            $importable instanceof AdStats => $this->importResult->incrementCreatedAdStats(),
            $importable instanceof DailyRevenue => $this->importResult->incrementCreatedDailyRevenues(),
            default => null,
        };
    }
}
