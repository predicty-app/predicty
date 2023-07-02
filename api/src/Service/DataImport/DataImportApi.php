<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\Ad;
use App\Entity\AdInsights;
use App\Entity\AdSet;
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

    public function upsertAdInsights(
        Ulid $userId,
        Ulid $accountId,
        Ulid $adId,
        Money $amountSpent,
        DateTimeImmutable $date,
        int $conversions = 0,
        int $clicks = 0,
        int $impressions = 0,
        int $leads = 0,
        ?Money $costPerClick = null,
        ?Money $costPerResult = null,
        ?Money $costPerMil = null
    ): AdInsights;

    public function upsertDailyRevenue(Ulid $userId, Ulid $accountId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue;
}
