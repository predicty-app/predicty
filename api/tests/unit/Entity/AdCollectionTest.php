<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AdCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\AdCollection
 */
class AdCollectionTest extends TestCase
{
    public function test_create_new_entity(): void
    {
        $entity = new AdCollection(123, 456, 'Test', [123, 456, 789]);
        $this->assertSame('Test', $entity->getName());
        $this->assertSame(123, $entity->getUserId());
        $this->assertSame([123, 456, 789], $entity->getAdsIds());
    }

    public function test_add_ads_ids(): void
    {
        $entity = new AdCollection(123, 456, 'Test');
        $entity->addAdsIds([123, 456, 789]);
        $this->assertSame([123, 456, 789], $entity->getAdsIds());
    }

    public function test_remove_ads_ids(): void
    {
        $entity = new AdCollection(123, 456, 'Test', [123, 456, 789]);
        $entity->removeAdsIds([456, 789]);

        $this->assertSame([123], $entity->getAdsIds());
    }
}
