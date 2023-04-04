<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\DailyRevenue;
use Brick\Money\Money;
use DateTimeImmutable;

interface DataImportApi
{
    public function getOrCreateCampaign(int $userId, string $name, string $externalId): Campaign;

    public function getOrCreateAd(AdSet $adSet, string $name, string $externalId): Ad;

    public function getOrCreateAdSet(Campaign $campaign, string $name, string $externalId): AdSet;

    public function getOrCreateAdStats(Ad $ad, DateTimeImmutable $date, int $results, Money $costPerResult, Money $amountSpent): AdStats;

    public function getOrCreateDailyRevenue(int $userId, DateTimeImmutable $date, Money $revenue, Money $averageOrderValue): DailyRevenue;
}
