<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\ImportResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\ImportResult
 */
class ImportResultTest extends TestCase
{
    public function test_increment_created_ads(): void
    {
        $entity = new ImportResult();
        $entity->incrementCreatedAds();
        $this->assertEquals(1, $entity->getCreatedAds());
    }

    public function test_to_array(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals([
            'createdCampaigns' => 1,
            'createdAdSets' => 2,
            'createdAds' => 3,
            'createdAdStats' => 4,
            'createdDailyRevenues' => 5,
        ], $entity->toArray());
    }

    public function test_get_created_campaigns(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(1, $entity->getCreatedCampaigns());
    }

    public function test__construct(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(1, $entity->getCreatedCampaigns());
        $this->assertEquals(2, $entity->getCreatedAdSets());
        $this->assertEquals(3, $entity->getCreatedAds());
        $this->assertEquals(4, $entity->getCreatedAdStats());
        $this->assertEquals(5, $entity->getCreatedDailyRevenues());
    }

    public function test_get_created_ad_stats(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(4, $entity->getCreatedAdStats());
    }

    public function test_empty(): void
    {
        $entity = ImportResult::empty();
        $this->assertEquals(0, $entity->getCreatedCampaigns());
        $this->assertEquals(0, $entity->getCreatedAdSets());
        $this->assertEquals(0, $entity->getCreatedAds());
        $this->assertEquals(0, $entity->getCreatedAdStats());
        $this->assertEquals(0, $entity->getCreatedDailyRevenues());
    }

    public function test_from_array(): void
    {
        $entity = ImportResult::fromArray([
            'createdCampaigns' => 1,
            'createdAdSets' => 2,
            'createdAds' => 3,
            'createdAdStats' => 4,
            'createdDailyRevenues' => 5,
        ]);

        $this->assertEquals(1, $entity->getCreatedCampaigns());
        $this->assertEquals(2, $entity->getCreatedAdSets());
        $this->assertEquals(3, $entity->getCreatedAds());
        $this->assertEquals(4, $entity->getCreatedAdStats());
        $this->assertEquals(5, $entity->getCreatedDailyRevenues());
    }

    public function test_increment_created_ad_stats(): void
    {
        $entity = new ImportResult();
        $entity->incrementCreatedAdStats();
        $this->assertEquals(1, $entity->getCreatedAdStats());
    }

    public function test_get_created_ads(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(3, $entity->getCreatedAds());
    }

    public function test_increment_created_daily_revenues(): void
    {
        $entity = new ImportResult();
        $entity->incrementCreatedDailyRevenues();
        $this->assertEquals(1, $entity->getCreatedDailyRevenues());
    }

    public function test_get_created_daily_revenues(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(5, $entity->getCreatedDailyRevenues());
    }

    public function test_increment_created_ad_sets(): void
    {
        $entity = new ImportResult();
        $entity->incrementCreatedAdSets();
        $this->assertEquals(1, $entity->getCreatedAdSets());
    }

    public function test_get_created_ad_sets(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(2, $entity->getCreatedAdSets());
    }

    public function test_increment_created_campaigns(): void
    {
        $entity = new ImportResult();
        $entity->incrementCreatedCampaigns();
        $this->assertEquals(1, $entity->getCreatedCampaigns());
    }

    public function test_get_total_created(): void
    {
        $entity = new ImportResult(
            createdCampaigns: 1,
            createdAdSets: 2,
            createdAds: 3,
            createdAdStats: 4,
            createdDailyRevenues: 5,
        );

        $this->assertEquals(15, $entity->getTotalCreated());
    }
}
