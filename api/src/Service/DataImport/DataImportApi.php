<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\DailyRevenue;
use App\Entity\DataProvider;
use Brick\Money\Money;
use DateTimeImmutable;
use Symfony\Component\Uid\Ulid;

interface DataImportApi
{
    public function setOnPersistCallback(callable $callback): void;

    public function setDefaultDataProvider(DataProvider $dataProvider): void;

    public function flush(): void;

    public function getCampaignByExternalId(Ulid $accountId, string $externalId): Campaign;

    public function getAdSetByExternalId(Ulid $accountId, string $externalId): AdSet;

    public function getAdByExternalId(Ulid $accountId, string $externalId): Ad;

    public function upsertCampaign(Ulid $userId, Ulid $accountId, string $name, string $externalId, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Campaign;

    public function upsertAdSet(Ulid $userId, Ulid $accountId, Ulid $campaignId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): AdSet;

    public function upsertAd(Ulid $userId, Ulid $accountId, Ulid $campaignId, Ulid $adSetId, string $externalId, string $name, ?DateTimeImmutable $startedAt = null, ?DateTimeImmutable $endedAt = null): Ad;

    public function upsertAdStats(Ulid $userId, Ulid $accountId, Ulid $adId, int $results, Money $costPerResult, Money $amountSpent, DateTimeImmutable $date): AdStats;

    public function upsertDailyRevenue(Ulid $userId, Ulid $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue;
}
